@extends('layouts.app')

@section('title', 'Laporan Anggota Perpustakaan Keliling')
@section('page_title', 'Laporan Data Anggota')
@section('page_subtitle', 'Rekapitulasi tahunan jumlah anggota perpustakaan keliling per sekolah dan gender')

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('laporan.anggota') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Laporan</label>
                <select name="tahun" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter Sekolah</label>
                <select name="id_sekolah" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Sekolah Binaan</option>
                    @foreach($sekolahList as $sek)
                        <option value="{{ $sek->id_sekolah }}" {{ $sekolahId == $sek->id_sekolah ? 'selected' : '' }}>
                            {{ $sek->nama_sekolah }} (Kec. {{ $sek->kecamatan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-5 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                    Tampilkan Data
                </button>
                @if($sekolahId)
                    <a href="{{ route('laporan.anggota') }}" class="text-xs text-slate-500 hover:text-slate-800 px-2 py-1">Reset</a>
                @endif
            </div>
        </form>

        <a href="{{ route('laporan.anggota.cetak', ['tahun' => $tahun, 'id_sekolah' => $sekolahId]) }}" target="_blank"
            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
            <i class="fa-solid fa-print mr-2"></i> Cetak Laporan Tahunan
        </a>
    </div>

    <!-- Summary Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h4 class="text-base font-bold text-slate-900">Rekapitulasi Anggota Per Sekolah (Tahun {{ $tahun }})</h4>
                <p class="text-xs text-slate-500">Distribusi jumlah pembaca berdasarkan gender dan kunjungan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Sekolah Binaan</th>
                        <th class="py-3.5 px-4">Kecamatan</th>
                        <th class="py-3.5 px-4 text-center">Laki-laki (L)</th>
                        <th class="py-3.5 px-4 text-center">Perempuan (P)</th>
                        <th class="py-3.5 px-4 text-center">Anggota Aktif</th>
                        <th class="py-3.5 px-4 text-center font-bold">Total Anggota</th>
                        <th class="py-3.5 px-4 text-center">Jml Kunjungan Mobil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php 
                        $grandTotal = 0; 
                        $totalL = 0; 
                        $totalP = 0; 
                        $totalKunjungan = 0; 
                    @endphp
                    @forelse($rekapSekolah as $idx => $row)
                        @php 
                            $grandTotal += $row->total_anggota;
                            $totalL += $row->anggota_laki;
                            $totalP += $row->anggota_perempuan;
                            $totalKunjungan += $row->total_kunjungan;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">{{ $row->nama_sekolah }}</td>
                            <td class="py-3.5 px-4 text-slate-600">Kec. {{ $row->kecamatan }}</td>
                            <td class="py-3.5 px-4 text-center font-semibold text-indigo-700 bg-indigo-50/40">{{ $row->anggota_laki }}</td>
                            <td class="py-3.5 px-4 text-center font-semibold text-pink-700 bg-pink-50/40">{{ $row->anggota_perempuan }}</td>
                            <td class="py-3.5 px-4 text-center font-medium text-emerald-700">{{ $row->anggota_aktif }}</td>
                            <td class="py-3.5 px-4 text-center font-extrabold text-slate-900 text-sm bg-slate-50/80">{{ $row->total_anggota }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">{{ $row->total_kunjungan }}x</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data rekapitulasi.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rekapSekolah->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-100/80 font-bold text-slate-900 border-t-2 border-slate-300">
                            <td colspan="3" class="py-3.5 px-4 text-right uppercase text-xs">Total Keseluruhan :</td>
                            <td class="py-3.5 px-4 text-center text-indigo-700">{{ $totalL }}</td>
                            <td class="py-3.5 px-4 text-center text-pink-700">{{ $totalP }}</td>
                            <td class="py-3.5 px-4 text-center">-</td>
                            <td class="py-3.5 px-4 text-center text-base font-extrabold text-indigo-900">{{ $grandTotal }} orang</td>
                            <td class="py-3.5 px-4 text-center">{{ $totalKunjungan }}x kunjungan</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Detail Anggota Table (Jika filter sekolah dipilih) -->
    @if($anggotaDetail)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200">
                <h4 class="text-base font-bold text-slate-900">Daftar Anggota Rinci di Sekolah Terpilih</h4>
                <p class="text-xs text-slate-500">Menampilkan seluruh data siswa yang terdaftar sebagai anggota</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">No. Anggota</th>
                            <th class="py-3 px-4">NISN</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4 text-center">L/P</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($anggotaDetail as $i => $ag)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2.5 px-4 text-center font-medium text-slate-400">{{ $i + 1 }}</td>
                                <td class="py-2.5 px-4 font-mono font-bold text-indigo-700">{{ $ag->nomor_anggota }}</td>
                                <td class="py-2.5 px-4 font-mono text-slate-500">{{ $ag->nisn ?: '-' }}</td>
                                <td class="py-2.5 px-4 font-bold text-slate-900">{{ $ag->nama }}</td>
                                <td class="py-2.5 px-4 text-center font-bold {{ $ag->jenis_kelamin == 'L' ? 'text-indigo-600' : 'text-pink-600' }}">{{ $ag->jenis_kelamin }}</td>
                                <td class="py-2.5 px-4 font-medium text-slate-700">{{ $ag->kelas }}</td>
                                <td class="py-2.5 px-4 capitalize font-semibold text-emerald-700">{{ $ag->status_anggota }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400">Belum ada siswa terdaftar di sekolah ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection

