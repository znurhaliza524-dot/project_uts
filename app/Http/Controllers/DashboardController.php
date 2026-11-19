<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\BeritaAcara;
use App\Models\DetailKunjungan;
use App\Models\Pustakawan;
use App\Models\Sekolah;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSekolah = Sekolah::count();
        $totalPustakawan = Pustakawan::count();
        $totalAnggota = Anggota::count();
        $totalBeritaAcara = BeritaAcara::count();
        $totalKunjunganSiswa = DetailKunjungan::count();

        $kunjunganTerbaru = BeritaAcara::with(['sekolah', 'pustakawan'])
            ->withCount('detailKunjungan')
            ->orderByDesc('tgl_kunjungan')
            ->orderByDesc('id_ba')
            ->take(5)
            ->get();

        $sekolahPopuler = Sekolah::withCount(['anggota', 'beritaAcara'])
            ->orderByDesc('anggota_count')
            ->take(5)
            ->get();

        $anggotaByGender = [
            'L' => Anggota::where('jenis_kelamin', 'L')->count(),
            'P' => Anggota::where('jenis_kelamin', 'P')->count(),
        ];

        // Statistik Kunjungan Bulanan untuk Chart
        $currentYear = date('Y');
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $kunjunganBulanan = array_fill(0, 12, 0);

        // Gunakan strftime untuk kompatibilitas MySQL & SQLite
        $isMySQL = config('database.default') === 'mysql';
        $monthExpr = $isMySQL
            ? 'MONTH(tgl_kunjungan) as bulan'
            : "CAST(strftime('%m', tgl_kunjungan) AS INTEGER) as bulan";

        $visits = BeritaAcara::whereYear('tgl_kunjungan', $currentYear)
            ->selectRaw("{$monthExpr}, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        foreach ($visits as $bulan => $total) {
            $kunjunganBulanan[(int)$bulan - 1] = (int)$total;
        }

        return view('dashboard.index', compact(
            'totalSekolah',
            'totalPustakawan',
            'totalAnggota',
            'totalBeritaAcara',
            'totalKunjunganSiswa',
            'kunjunganTerbaru',
            'sekolahPopuler',
            'anggotaByGender',
            'bulanLabels',
            'kunjunganBulanan',
            'currentYear'
        ));
    }
}
