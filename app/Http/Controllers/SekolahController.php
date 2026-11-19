<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $sekolah = Sekolah::withCount(['anggota', 'beritaAcara'])
            ->when($search, function ($query, $search) {
                $query->where('nama_sekolah', 'like', "%{$search}%")
                      ->orWhere('npsn', 'like', "%{$search}%")
                      ->orWhere('nama_kepsek', 'like', "%{$search}%")
                      ->orWhere('kecamatan', 'like', "%{$search}%");
            })
            ->orderBy('nama_sekolah')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('sekolah._table', compact('sekolah', 'search'));
        }

        return view('sekolah.index', compact('sekolah', 'search'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('sekolah.index')->with('error', 'Akses dibatasi. Penambahan data sekolah hanya dapat dilakukan oleh Administrator.');
        }

        return view('sekolah.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang berwenang menambah data sekolah.');
        }

        $validated = $request->validate([
            'npsn'           => 'nullable|string|max:20|unique:sekolah,npsn',
            'nama_sekolah'   => 'required|string|max:100',
            'nama_kepsek'    => 'required|string|max:100',
            'alamat'         => 'required|string',
            'kecamatan'      => 'required|string|max:50',
            'kontak_sekolah' => 'nullable|string|max:25',
        ]);

        Sekolah::create($validated);

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit(Sekolah $sekolah)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('sekolah.index')->with('error', 'Akses dibatasi. Perubahan data sekolah hanya dapat dilakukan oleh Administrator.');
        }

        return view('sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang berwenang mengubah data sekolah.');
        }

        $validated = $request->validate([
            'npsn'           => 'nullable|string|max:20|unique:sekolah,npsn,' . $sekolah->id_sekolah . ',id_sekolah',
            'nama_sekolah'   => 'required|string|max:100',
            'nama_kepsek'    => 'required|string|max:100',
            'alamat'         => 'required|string',
            'kecamatan'      => 'required|string|max:50',
            'kontak_sekolah' => 'nullable|string|max:25',
        ]);

        $sekolah->update($validated);

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Administrator yang berwenang menghapus master data sekolah.');
        }

        if ($sekolah->anggota()->count() > 0 || $sekolah->beritaAcara()->count() > 0) {
            return back()->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki relasi data siswa atau riwayat berita acara kunjungan.');
        }

        $sekolah->delete();
        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil dihapus.');
    }
}

