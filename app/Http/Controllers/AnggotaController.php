<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sekolahId = $request->query('id_sekolah');
        $gender = $request->query('jenis_kelamin');

        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();

        $anggota = Anggota::with('sekolah')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor_anggota', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%")
                      ->orWhere('kelas', 'like', "%{$search}%");
                });
            })
            ->when($sekolahId, function ($query, $sekolahId) {
                $query->where('id_sekolah', $sekolahId);
            })
            ->when($gender, function ($query, $gender) {
                $query->where('jenis_kelamin', $gender);
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view('anggota._table', compact('anggota', 'search', 'sekolahId', 'gender'));
        }

        return view('anggota.index', compact('anggota', 'sekolahList', 'search', 'sekolahId', 'gender'));
    }

    public function create()
    {
        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();

        // Rekomendasi Nomor Anggota Baru
        $year = date('Y');
        $lastAnggota = Anggota::latest('id_anggota')->first();
        $nextNumber = $lastAnggota ? ($lastAnggota->id_anggota + 1) : 1;
        $autoNomor = sprintf('AGT-%s-%04d', $year, $nextNumber);

        return view('anggota.create', compact('sekolahList', 'autoNomor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sekolah'     => 'required|exists:sekolah,id_sekolah',
            'nomor_anggota'  => 'required|string|max:30|unique:anggota,nomor_anggota',
            'nisn'           => 'nullable|string|max:20',
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'kelas'          => 'required|string|max:20',
            'alamat'         => 'nullable|string',
            'status_anggota' => 'required|in:aktif,lulus,pindah',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')->with('success', 'Anggota perpustakaan berhasil didaftarkan.');
    }

    public function edit(Anggota $anggota)
    {
        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();
        return view('anggota.edit', compact('anggota', 'sekolahList'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'id_sekolah'     => 'required|exists:sekolah,id_sekolah',
            'nomor_anggota'  => 'required|string|max:30|unique:anggota,nomor_anggota,' . $anggota->id_anggota . ',id_anggota',
            'nisn'           => 'nullable|string|max:20',
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'kelas'          => 'required|string|max:20',
            'alamat'         => 'nullable|string',
            'status_anggota' => 'required|in:aktif,lulus,pindah',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->detailKunjungan()->count() > 0) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena sudah memiliki riwayat presensi kunjungan mobil keliling.');
        }

        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }

    public function cetakKartu(Anggota $anggota)
    {
        $anggota->load('sekolah');
        return view('anggota.kartu', compact('anggota'));
    }

    public function cetakKartuMassal(Request $request)
    {
        $sekolahId = $request->query('id_sekolah');
        $kelas = $request->query('kelas');

        $sekolah = $sekolahId ? Sekolah::find($sekolahId) : null;

        $anggotaList = Anggota::with('sekolah')
            ->where('status_anggota', 'aktif')
            ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
            ->when($kelas, fn($q) => $q->where('kelas', $kelas))
            ->orderBy('id_sekolah')
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        if ($request->ajax()) {
            return view('anggota._modal_kartu_massal', compact('anggotaList', 'sekolah', 'kelas'));
        }

        return view('anggota.kartu_massal', compact('anggotaList', 'sekolah', 'kelas'));
    }

    public function exportCsv(Request $request)
    {
        $sekolahId = $request->query('id_sekolah');
        $gender = $request->query('jenis_kelamin');

        $anggota = Anggota::with('sekolah')
            ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
            ->when($gender, fn($q) => $q->where('jenis_kelamin', $gender))
            ->orderBy('id_sekolah')
            ->orderBy('kelas')
            ->orderBy('nama')
            ->get();

        $filename = 'data-anggota-perpustakaan-keliling-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($anggota) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Nomor Anggota', 'Nama Siswa', 'NISN', 'Jenis Kelamin', 'Sekolah', 'Kelas', 'Status', 'Alamat']);

            foreach ($anggota as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->nomor_anggota,
                    $row->nama,
                    $row->nisn ?: '-',
                    $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $row->sekolah->nama_sekolah ?? '-',
                    $row->kelas,
                    ucfirst($row->status_anggota),
                    $row->alamat ?: '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

