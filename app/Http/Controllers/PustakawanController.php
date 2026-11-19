<?php

namespace App\Http\Controllers;

use App\Models\Pustakawan;
use Illuminate\Http\Request;

class PustakawanController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Pengelolaan data pustakawan khusus wewenang Administrator.');
        }

        $search = $request->query('search');

        $pustakawan = Pustakawan::withCount('beritaAcara')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pustakawan._table', compact('pustakawan', 'search'));
        }

        return view('pustakawan.index', compact('pustakawan', 'search'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
        return view('pustakawan.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nip'     => 'required|string|max:30|unique:pustakawan,nip',
            'nama'    => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_telp' => 'nullable|string|max:20',
        ]);

        Pustakawan::create($validated);

        return redirect()->route('pustakawan.index')->with('success', 'Data pustakawan berhasil ditambahkan.');
    }

    public function edit(Pustakawan $pustakawan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
        return view('pustakawan.edit', compact('pustakawan'));
    }

    public function update(Request $request, Pustakawan $pustakawan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nip'     => 'required|string|max:30|unique:pustakawan,nip,' . $pustakawan->id_pustakawan . ',id_pustakawan',
            'nama'    => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $pustakawan->update($validated);

        return redirect()->route('pustakawan.index')->with('success', 'Data pustakawan berhasil diperbarui.');
    }

    public function destroy(Pustakawan $pustakawan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        if ($pustakawan->beritaAcara()->count() > 0) {
            return back()->with('error', 'Pustakawan tidak dapat dihapus karena sudah memiliki riwayat tugas pada Berita Acara kunjungan.');
        }

        $pustakawan->delete();
        return redirect()->route('pustakawan.index')->with('success', 'Data pustakawan berhasil dihapus.');
    }
}

