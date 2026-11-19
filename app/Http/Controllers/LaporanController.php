<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\BeritaAcara;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function anggota(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $sekolahId = $request->query('id_sekolah');

        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();

        // Rekapitulasi per sekolah
        $rekapSekolah = Sekolah::withCount([
            'anggota as total_anggota',
            'anggota as anggota_laki' => fn($q) => $q->where('jenis_kelamin', 'L'),
            'anggota as anggota_perempuan' => fn($q) => $q->where('jenis_kelamin', 'P'),
            'anggota as anggota_aktif' => fn($q) => $q->where('status_anggota', 'aktif'),
            'beritaAcara as total_kunjungan' => function($q) use ($tahun) {
                if ($tahun) {
                    $q->whereYear('tgl_kunjungan', $tahun);
                }
            }
        ])
        ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
        ->get();

        // Data anggota detail jika sekolah dipilih
        $anggotaDetail = null;
        if ($sekolahId) {
            $anggotaDetail = Anggota::where('id_sekolah', $sekolahId)
                ->orderBy('kelas')
                ->orderBy('nama')
                ->get();
        }

        return view('laporan.anggota', compact('rekapSekolah', 'sekolahList', 'tahun', 'sekolahId', 'anggotaDetail'));
    }

    public function cetakAnggota(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $sekolahId = $request->query('id_sekolah');

        $sekolah = $sekolahId ? Sekolah::find($sekolahId) : null;

        $rekapSekolah = Sekolah::withCount([
            'anggota as total_anggota',
            'anggota as anggota_laki' => fn($q) => $q->where('jenis_kelamin', 'L'),
            'anggota as anggota_perempuan' => fn($q) => $q->where('jenis_kelamin', 'P'),
            'beritaAcara as total_kunjungan' => function($q) use ($tahun) {
                if ($tahun) {
                    $q->whereYear('tgl_kunjungan', $tahun);
                }
            }
        ])
        ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
        ->get();

        $anggotaList = null;
        if ($sekolahId) {
            $anggotaList = Anggota::where('id_sekolah', $sekolahId)
                ->orderBy('kelas')
                ->orderBy('nama')
                ->get();
        }

        return view('laporan.cetak_anggota', compact('rekapSekolah', 'sekolah', 'tahun', 'anggotaList'));
    }

    public function kunjungan(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $bulan = $request->query('bulan');
        $sekolahId = $request->query('id_sekolah');

        $sekolahList = Sekolah::orderBy('nama_sekolah')->get();

        $beritaAcara = BeritaAcara::with(['sekolah', 'pustakawan'])
            ->withCount('detailKunjungan')
            ->when($tahun, fn($q) => $q->whereYear('tgl_kunjungan', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tgl_kunjungan', $bulan))
            ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
            ->orderBy('tgl_kunjungan')
            ->get();

        $totalKunjungan = $beritaAcara->count();
        $totalPengunjung = $beritaAcara->sum('detail_kunjungan_count');

        return view('laporan.kunjungan', compact('beritaAcara', 'sekolahList', 'tahun', 'bulan', 'sekolahId', 'totalKunjungan', 'totalPengunjung'));
    }

    public function cetakKunjungan(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $bulan = $request->query('bulan');
        $sekolahId = $request->query('id_sekolah');

        $sekolah = $sekolahId ? Sekolah::find($sekolahId) : null;

        $beritaAcara = BeritaAcara::with(['sekolah', 'pustakawan'])
            ->withCount('detailKunjungan')
            ->when($tahun, fn($q) => $q->whereYear('tgl_kunjungan', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tgl_kunjungan', $bulan))
            ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
            ->orderBy('tgl_kunjungan')
            ->get();

        $totalKunjungan = $beritaAcara->count();
        $totalPengunjung = $beritaAcara->sum('detail_kunjungan_count');

        return view('laporan.cetak_kunjungan', compact('beritaAcara', 'sekolah', 'tahun', 'bulan', 'totalKunjungan', 'totalPengunjung'));
    }

    public function exportCsvKunjungan(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $bulan = $request->query('bulan');
        $sekolahId = $request->query('id_sekolah');

        $beritaAcara = BeritaAcara::with(['sekolah', 'pustakawan'])
            ->withCount('detailKunjungan')
            ->when($tahun, fn($q) => $q->whereYear('tgl_kunjungan', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tgl_kunjungan', $bulan))
            ->when($sekolahId, fn($q) => $q->where('id_sekolah', $sekolahId))
            ->orderBy('tgl_kunjungan')
            ->get();

        $filename = 'rekapitulasi-kunjungan-perpustakaan-keliling-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($beritaAcara) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Tanggal', 'Nomor Berita Acara', 'Sekolah Tujuan', 'Kecamatan', 'Pustakawan', 'Perwakilan Sekolah', 'Jumlah Siswa Hadir']);

            foreach ($beritaAcara as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    \Carbon\Carbon::parse($row->tgl_kunjungan)->format('d/m/Y'),
                    $row->nomor_ba,
                    $row->sekolah->nama_sekolah ?? '-',
                    $row->sekolah->kecamatan ?? '-',
                    $row->pustakawan->nama ?? '-',
                    $row->nama_perwakilan_sekolah,
                    $row->detail_kunjungan_count,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

