<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\BeritaAcara;
use App\Models\DetailKunjungan;
use App\Models\Pustakawan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sekolahId = $request->query('id_sekolah');
        $tglAwal = $request->query('tgl_awal');
        $tglAkhir = $request->query('tgl_akhir');

        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();

        $beritaAcara = BeritaAcara::with(['sekolah', 'pustakawan'])
            ->withCount('detailKunjungan')
            ->when($search, function ($query, $search) {
                $query->where('nomor_ba', 'like', "%{$search}%")
                      ->orWhere('nama_perwakilan_sekolah', 'like', "%{$search}%")
                      ->orWhereHas('sekolah', fn($q) => $q->where('nama_sekolah', 'like', "%{$search}%"))
                      ->orWhereHas('pustakawan', fn($q) => $q->where('nama', 'like', "%{$search}%"));
            })
            ->when($sekolahId, function ($query, $sekolahId) {
                $query->where('id_sekolah', $sekolahId);
            })
            ->when($tglAwal, function ($query, $tglAwal) {
                $query->whereDate('tgl_kunjungan', '>=', $tglAwal);
            })
            ->when($tglAkhir, function ($query, $tglAkhir) {
                $query->whereDate('tgl_kunjungan', '<=', $tglAkhir);
            })
            ->orderByDesc('tgl_kunjungan')
            ->orderByDesc('id_ba')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('berita_acara._table', compact('beritaAcara', 'search', 'sekolahId', 'tglAwal', 'tglAkhir'));
        }

        return view('berita_acara.index', compact('beritaAcara', 'sekolahList', 'search', 'sekolahId', 'tglAwal', 'tglAkhir'));
    }

    public function create(Request $request)
    {
        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();
        $pustakawanList = Pustakawan::orderBy('nama')->get();

        // Rekomendasi Nomor BA otomatis
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $bulanRomawi = $romawi[(int)date('n')];
        $countThisMonth = BeritaAcara::whereYear('tgl_kunjungan', date('Y'))
            ->whereMonth('tgl_kunjungan', date('n'))
            ->count() + 1;
        $autoNomorBa = sprintf('BA/%s/%s/%03d', date('Y'), $bulanRomawi, $countThisMonth);

        // Jika sekolah dipilih lewat query param
        $selectedSekolahId = $request->query('id_sekolah', $sekolahList->first()?->id_sekolah);
        $anggotaList = Anggota::where('id_sekolah', $selectedSekolahId)->where('status_anggota', 'aktif')->orderBy('nama')->get();

        return view('berita_acara.create', compact('sekolahList', 'pustakawanList', 'autoNomorBa', 'selectedSekolahId', 'anggotaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_ba'                => 'required|string|max:50|unique:berita_acara,nomor_ba',
            'id_sekolah'              => 'required|exists:sekolah,id_sekolah',
            'id_pustakawan'           => 'required|exists:pustakawan,id_pustakawan',
            'tgl_kunjungan'           => 'required|date',
            'waktu_mulai'             => 'nullable',
            'waktu_selesai'           => 'nullable',
            'nama_perwakilan_sekolah' => 'required|string|max:100',
            'nip_perwakilan_sekolah'  => 'nullable|string|max:30',
            'catatan'                 => 'nullable|string',
            'anggota_hadir'           => 'nullable|array',
            'anggota_hadir.*'         => 'exists:anggota,id_anggota',
        ]);

        DB::transaction(function () use ($validated) {
            $ba = BeritaAcara::create([
                'nomor_ba'                => $validated['nomor_ba'],
                'id_sekolah'              => $validated['id_sekolah'],
                'id_pustakawan'           => $validated['id_pustakawan'],
                'tgl_kunjungan'           => $validated['tgl_kunjungan'],
                'waktu_mulai'             => $validated['waktu_mulai'] ?: null,
                'waktu_selesai'           => $validated['waktu_selesai'] ?: null,
                'nama_perwakilan_sekolah' => $validated['nama_perwakilan_sekolah'],
                'nip_perwakilan_sekolah'  => $validated['nip_perwakilan_sekolah'] ?: null,
                'catatan'                 => $validated['catatan'] ?: null,
            ]);

            if (!empty($validated['anggota_hadir'])) {
                foreach ($validated['anggota_hadir'] as $idAnggota) {
                    DetailKunjungan::create([
                        'id_ba'      => $ba->id_ba,
                        'id_anggota' => $idAnggota,
                    ]);
                }
            }
        });

        return redirect()->route('berita-acara.index')->with('success', 'Berita Acara dan Presensi Siswa berhasil disimpan.');
    }

    public function show(BeritaAcara $beritaAcara)
    {
        $beritaAcara->load(['sekolah', 'pustakawan', 'pengunjung' => function($q) {
            $q->orderBy('nama');
        }]);

        return view('berita_acara.show', compact('beritaAcara'));
    }

    public function edit(BeritaAcara $beritaAcara)
    {
        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();
        $pustakawanList = Pustakawan::orderBy('nama')->get();

        $anggotaList = Anggota::where('id_sekolah', $beritaAcara->id_sekolah)->where('status_anggota', 'aktif')->orderBy('nama')->get();
        $hadirIds = $beritaAcara->detailKunjungan()->pluck('id_anggota')->toArray();

        return view('berita_acara.edit', compact('beritaAcara', 'sekolahList', 'pustakawanList', 'anggotaList', 'hadirIds'));
    }

    public function update(Request $request, BeritaAcara $beritaAcara)
    {
        $validated = $request->validate([
            'nomor_ba'                => 'required|string|max:50|unique:berita_acara,nomor_ba,' . $beritaAcara->id_ba . ',id_ba',
            'id_sekolah'              => 'required|exists:sekolah,id_sekolah',
            'id_pustakawan'           => 'required|exists:pustakawan,id_pustakawan',
            'tgl_kunjungan'           => 'required|date',
            'waktu_mulai'             => 'nullable',
            'waktu_selesai'           => 'nullable',
            'nama_perwakilan_sekolah' => 'required|string|max:100',
            'nip_perwakilan_sekolah'  => 'nullable|string|max:30',
            'catatan'                 => 'nullable|string',
            'anggota_hadir'           => 'nullable|array',
            'anggota_hadir.*'         => 'exists:anggota,id_anggota',
        ]);

        DB::transaction(function () use ($validated, $beritaAcara) {
            $beritaAcara->update([
                'nomor_ba'                => $validated['nomor_ba'],
                'id_sekolah'              => $validated['id_sekolah'],
                'id_pustakawan'           => $validated['id_pustakawan'],
                'tgl_kunjungan'           => $validated['tgl_kunjungan'],
                'waktu_mulai'             => $validated['waktu_mulai'] ?: null,
                'waktu_selesai'           => $validated['waktu_selesai'] ?: null,
                'nama_perwakilan_sekolah' => $validated['nama_perwakilan_sekolah'],
                'nip_perwakilan_sekolah'  => $validated['nip_perwakilan_sekolah'] ?: null,
                'catatan'                 => $validated['catatan'] ?: null,
            ]);

            // Sync detail kunjungan
            $beritaAcara->detailKunjungan()->delete();
            if (!empty($validated['anggota_hadir'])) {
                foreach ($validated['anggota_hadir'] as $idAnggota) {
                    DetailKunjungan::create([
                        'id_ba'      => $beritaAcara->id_ba,
                        'id_anggota' => $idAnggota,
                    ]);
                }
            }
        });

        return redirect()->route('berita-acara.show', $beritaAcara->id_ba)->with('success', 'Berita Acara berhasil diperbarui.');
    }

    public function destroy(BeritaAcara $beritaAcara)
    {
        $beritaAcara->detailKunjungan()->delete();
        $beritaAcara->delete();

        return redirect()->route('berita-acara.index')->with('success', 'Berita Acara berhasil dihapus.');
    }

    public function cetak(BeritaAcara $beritaAcara)
    {
        $beritaAcara->load(['sekolah', 'pustakawan', 'pengunjung' => function($q) {
            $q->orderBy('nama');
        }]);

        return view('berita_acara.cetak', compact('beritaAcara'));
    }

    // Helper endpoint untuk mengambil siswa saat dropdown sekolah berubah
    public function getAnggotaBySekolah($idSekolah)
    {
        $anggota = Anggota::where('id_sekolah', $idSekolah)
            ->where('status_anggota', 'aktif')
            ->orderBy('nama')
            ->get(['id_anggota', 'nomor_anggota', 'nama', 'kelas', 'jenis_kelamin']);

        return response()->json($anggota);
    }
}

