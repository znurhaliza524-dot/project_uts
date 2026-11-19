@extends('layouts.app')

@section('title', 'Laporan Kunjungan Mobil Keliling')
@section('page_title', 'Laporan Kunjungan Mobil Keliling')
@section('page_subtitle', 'Rekapitulasi operasional armada pelayanan perpustakaan keliling dan pembaca yang dilayani')

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('laporan.kunjungan') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun</label>
                <select name="tahun" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bulan</label>
                <select name="bulan" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Bulan</option>
                    @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $num => $namaBulan)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter Sekolah</label>
                <select name="id_sekolah" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Sekolah</option>
                    @foreach($sekolahList as $sek)
                        <option value="{{ $sek->id_sekolah }}" {{ $sekolahId == $sek->id_sekolah ? 'selected' : '' }}>
                            {{ $sek->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-5 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                    Tampilkan
                </button>
                @if($tahun || $bulan || $sekolahId)
                    <a href="{{ route('laporan.kunjungan') }}" class="text-xs text-slate-500 hover:text-slate-800 px-2 py-1">Reset</a>
                @endif
            </div>
        </form>

        <div class="w-full md:w-auto flex flex-wrap items-center gap-2">
            <a href="{{ route('laporan.kunjungan.csv', ['tahun' => $tahun, 'bulan' => $bulan, 'id_sekolah' => $sekolahId]) }}"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 shadow-sm transition">
                <i class="fa-solid fa-file-excel mr-2"></i> Unduh CSV / Excel
            </a>
            <a href="{{ route('laporan.kunjungan.cetak', ['tahun' => $tahun, 'bulan' => $bulan, 'id_sekolah' => $sekolahId]) }}" target="_blank"
                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-print mr-2"></i> Cetak Rekapitulasi
            </a>
        </div>
    </div>

    <!-- Summary Statistics Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-5 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider block">Total Kunjungan Mobil</span>
                <span class="text-2xl font-black text-indigo-950 mt-1 block">{{ $totalKunjungan }} kali pelayanan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow">
                <i class="fa-solid fa-van-shuttle"></i>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Total Pengunjung Terlayani</span>
                <span class="text-2xl font-black text-emerald-950 mt-1 block">{{ number_format($totalPengunjung) }} orang siswa</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <h4 class="text-base font-bold text-slate-900">Rekapitulasi Kunjungan Armada Layanan Keliling</h4>
            <p class="text-xs text-slate-500">Daftar Berita Acara yang diterbitkan selama periode terpilih</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">No. Berita Acara</th>
                        <th class="py-3.5 px-4">Sekolah Tujuan</th>
                        <th class="py-3.5 px-4">Petugas Pustakawan</th>
                        <th class="py-3.5 px-4">Perwakilan Sekolah</th>
                        <th class="py-3.5 px-4 text-center font-bold">Jml Pengunjung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($beritaAcara as $i => $ba)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $i + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::parse($ba->tgl_kunjungan)->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-indigo-700">
                                {{ $ba->nomor_ba }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $ba->sekolah->nama_sekolah ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400">Kec. {{ $ba->sekolah->kecamatan ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ $ba->pustakawan->nama ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $ba->nama_perwakilan_sekolah }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-extrabold text-emerald-700 bg-emerald-50/50">
                                {{ $ba->detail_kunjungan_count }} siswa
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada riwayat kunjungan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

