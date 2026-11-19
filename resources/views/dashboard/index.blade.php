@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Ringkasan operasional dan statistik layanan perpustakaan keliling')

@section('content')
<div class="space-y-3">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        
        <!-- Card 1: Sekolah -->
        <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-base flex-shrink-0">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider truncate">Sekolah Terdaftar</p>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 flex-shrink-0">Master</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ number_format($totalSekolah) }}</h3>
                <p class="text-[10px] text-slate-400 flex items-center mt-0.5 truncate">
                    <i class="fa-solid fa-location-dot mr-1"></i>Kab. Lampung Timur
                </p>
            </div>
        </div>

        <!-- Card 2: Pustakawan -->
        <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-base flex-shrink-0">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider truncate">Pustakawan Aktif</p>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-teal-50 text-teal-700 flex-shrink-0">Petugas</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ number_format($totalPustakawan) }}</h3>
                <p class="text-[10px] text-slate-400 flex items-center mt-0.5 truncate">
                    <i class="fa-solid fa-id-badge mr-1"></i>Tim operasional keliling
                </p>
            </div>
        </div>

        <!-- Card 3: Anggota Siswa -->
        <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base flex-shrink-0">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider truncate">Anggota Siswa</p>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 flex-shrink-0">Pembaca</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ number_format($totalAnggota) }}</h3>
                <p class="text-[10px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                    <span class="text-indigo-600 font-semibold"><i class="fa-solid fa-mars mr-0.5"></i>{{ $anggotaByGender['L'] }}L</span>
                    <span>&bull;</span>
                    <span class="text-pink-600 font-semibold"><i class="fa-solid fa-venus mr-0.5"></i>{{ $anggotaByGender['P'] }}P</span>
                </p>
            </div>
        </div>

        <!-- Card 4: Kunjungan (Berita Acara) -->
        <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-sm hover:shadow-md transition flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-base flex-shrink-0">
                <i class="fa-solid fa-van-shuttle"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider truncate">Kunjungan Armada</p>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-sky-50 text-sky-700 flex-shrink-0">Layanan</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ number_format($totalBeritaAcara) }}</h3>
                <p class="text-[10px] text-emerald-600 font-semibold flex items-center mt-0.5 truncate">
                    <i class="fa-solid fa-users mr-1"></i>{{ number_format($totalKunjunganSiswa) }} siswa dilayani
                </p>
            </div>
        </div>

    </div>

    <!-- Quick Actions Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-2xl p-4 md:p-5 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-3">
        <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                Layanan Mobil Keliling
            </span>
            <h3 class="text-base font-bold mt-1">Armada Mobil Perpustakaan Siap Meluncur?</h3>
            <p class="text-slate-300 text-[11px] mt-0.5 max-w-xl">
                Catat kegiatan kunjungan hari ini. Generate otomatis Berita Acara resmi dan presensi pembaca.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
            <a href="{{ route('berita-acara.create') }}" class="px-4 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 font-bold text-xs transition shadow-lg shadow-indigo-500/40 flex items-center">
                <i class="fa-solid fa-file-circle-plus mr-2"></i> Buat Berita Acara
            </a>
            <a href="{{ route('anggota.create') }}" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 font-bold text-xs transition border border-slate-700 flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Registrasi Siswa
            </a>
        </div>
    </div>

    <!-- INTERACTIVE CHARTS SECTION (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        
        <!-- Chart 1: Tren Kunjungan Bulanan (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/80 shadow-sm p-3.5 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Grafik Kunjungan ({{ $currentYear }})</h4>
                    <p class="text-[10px] text-slate-500">Frekuensi pelayanan mobil keliling per bulan</p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <i class="fa-solid fa-chart-line mr-1"></i> Tren Bulanan
                </span>
            </div>
            <div class="flex-1 relative" style="height:150px">
                <canvas id="chartKunjungan"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Gender Anggota (1 Col) -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-3.5 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Komposisi Pembaca</h4>
                    <p class="text-[10px] text-slate-500">Perbandingan gender anggota siswa</p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-pink-50 text-pink-700 border border-pink-200">
                    <i class="fa-solid fa-venus-mars mr-1"></i> Demografi
                </span>
            </div>
            <div class="flex-1 relative flex items-center justify-center" style="height:120px">
                <canvas id="chartGender"></canvas>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-100 grid grid-cols-2 text-center text-[10px]">
                <div>
                    <span class="text-slate-400 block">Laki-laki</span>
                    <span class="font-extrabold text-indigo-700 text-xs">{{ $anggotaByGender['L'] }} siswa</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Perempuan</span>
                    <span class="font-extrabold text-pink-700 text-xs">{{ $anggotaByGender['P'] }} siswa</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Two-Column Content: Latest Visits & School Ranking -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

        <!-- Left 2 Cols: Latest Berita Acara -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-2.5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Kunjungan Terakhir (Berita Acara)</h4>
                    <p class="text-[10px] text-slate-500">5 riwayat pelayanan perpustakaan keliling terbaru</p>
                </div>
                <a href="{{ route('berita-acara.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-[11px] border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-2.5 px-3">Nomor BA</th>
                            <th class="py-2.5 px-3">Sekolah Tujuan</th>
                            <th class="py-2.5 px-3">Pustakawan</th>
                            <th class="py-2.5 px-3 text-center">Pengunjung</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kunjunganTerbaru as $ba)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2 px-3">
                                    <span class="font-mono font-bold text-slate-900">{{ $ba->nomor_ba }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($ba->tgl_kunjungan)->format('d M Y') }}</p>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="font-semibold text-slate-900">{{ $ba->sekolah->nama_sekolah ?? '-' }}</span>
                                    <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $ba->sekolah->kecamatan ?? '-' }}</p>
                                </td>
                                <td class="py-2 px-3 text-slate-700">
                                    {{ $ba->pustakawan->nama ?? '-' }}
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-user-check mr-1"></i> {{ $ba->detail_kunjungan_count }} siswa
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-right space-x-1">
                                    <a href="{{ route('berita-acara.show', $ba->id_ba) }}" class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Detail">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                    </a>
                                    <a href="{{ route('berita-acara.cetak', $ba->id_ba) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition" title="Cetak Berita Acara">
                                        <i class="fa-solid fa-print text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">
                                    <i class="fa-regular fa-folder-open text-xl mb-1 block"></i>
                                    <p class="text-xs">Belum ada data berita acara kunjungan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 Col: School Directory / Stats -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-3.5 flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900 mb-0.5">Sekolah Terbanyak Siswa</h4>
                <p class="text-[10px] text-slate-500 mb-2.5">Distribusi keanggotaan per sekolah</p>

                <div class="space-y-2">
                    @forelse($sekolahPopuler as $sek)
                        <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-800 mb-0.5">
                                <span class="truncate pr-2">{{ $sek->nama_sekolah }}</span>
                                <span class="text-indigo-600 flex-shrink-0">{{ $sek->anggota_count }} siswa</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <span>Kec. {{ $sek->kecamatan }}</span>
                                <span>{{ $sek->berita_acara_count }}x kunjungan</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-slate-400 text-center py-4">Belum ada sekolah terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Report Shortcuts -->
            <div class="mt-3 pt-3 border-t border-slate-100">
                <p class="text-[11px] font-bold text-slate-900 mb-1.5">Pusat Cetak Laporan</p>
                <div class="grid grid-cols-2 gap-1.5">
                    <a href="{{ route('laporan.anggota') }}" class="p-2 text-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition">
                        <i class="fa-solid fa-users text-indigo-600 block mb-0.5"></i> Lap. Anggota
                    </a>
                    <a href="{{ route('laporan.kunjungan') }}" class="p-2 text-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition">
                        <i class="fa-solid fa-file-lines text-sky-600 block mb-0.5"></i> Lap. Kunjungan
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Kunjungan Bulanan (Bar Chart)
    const ctxKunjungan = document.getElementById('chartKunjungan').getContext('2d');
    new Chart(ctxKunjungan, {
        type: 'bar',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: @json($kunjunganBulanan),
                backgroundColor: 'rgba(99, 102, 241, 0.85)',
                hoverBackgroundColor: 'rgba(79, 70, 229, 1)',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: 'rgba(241, 245, 249, 1)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // 2. Chart Gender (Doughnut Chart)
    const ctxGender = document.getElementById('chartGender').getContext('2d');
    new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{{ $anggotaByGender['L'] }}, {{ $anggotaByGender['P'] }}],
                backgroundColor: ['#6366f1', '#ec4899'],
                hoverBackgroundColor: ['#4f46e5', '#db2777'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endpush
