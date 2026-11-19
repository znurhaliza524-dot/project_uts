@extends('layouts.app')

@section('title', 'Detail Berita Acara ' . $beritaAcara->nomor_ba)
@section('page_title', 'Detail Berita Acara Kunjungan')
@section('page_subtitle', 'Dokumen Berita Acara Nomor: ' . $beritaAcara->nomor_ba)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('berita-acara.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
        <div class="flex items-center space-x-2">
            <a href="{{ route('berita-acara.cetak', $beritaAcara->id_ba) }}" target="_blank" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak Dokumen Resmi
            </a>
            <a href="{{ route('berita-acara.edit', $beritaAcara->id_ba) }}" class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs transition">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Header Banner -->
        <div class="p-6 md:p-8 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                    BERITA ACARA RESMI
                </span>
                <h3 class="text-xl md:text-2xl font-mono font-bold mt-2 text-white">{{ $beritaAcara->nomor_ba }}</h3>
                <p class="text-xs text-slate-300 mt-1">
                    <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    <span class="mx-2">&bull;</span>
                    <i class="fa-regular fa-clock mr-1"></i> {{ $beritaAcara->waktu_mulai ? substr($beritaAcara->waktu_mulai, 0, 5) : '-' }} s/d {{ $beritaAcara->waktu_selesai ? substr($beritaAcara->waktu_selesai, 0, 5) : '-' }} WIB
                </p>
            </div>
            <div class="flex-shrink-0 text-left md:text-right">
                <span class="text-[11px] text-slate-400 block uppercase tracking-wider">Total Siswa Hadir</span>
                <span class="text-3xl font-extrabold text-emerald-400 font-mono">{{ $beritaAcara->pengunjung->count() }}</span>
                <span class="text-xs text-slate-300 block">Siswa Pembaca</span>
            </div>
        </div>

        <!-- 2 Parties Meta Grid -->
        <div class="p-6 md:p-8 border-b border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pihak 1 (Pustakawan) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block mb-2">Pihak Pertama (Petugas Dinas)</span>
                <h4 class="text-sm font-bold text-slate-900">{{ $beritaAcara->pustakawan->nama ?? '-' }}</h4>
                <p class="text-xs text-slate-500 font-mono">NIP: {{ $beritaAcara->pustakawan->nip ?? '-' }}</p>
                <p class="text-xs text-slate-600 mt-1">{{ $beritaAcara->pustakawan->jabatan ?? 'Petugas Perpustakaan Keliling' }}</p>
            </div>

            <!-- Pihak 2 (Perwakilan Sekolah) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block mb-2">Pihak Kedua (Pihak Sekolah)</span>
                <h4 class="text-sm font-bold text-slate-900">{{ $beritaAcara->nama_perwakilan_sekolah }}</h4>
                <p class="text-xs text-slate-500 font-mono">NIP: {{ $beritaAcara->nip_perwakilan_sekolah ?: '-' }}</p>
                <p class="text-xs text-slate-600 mt-1">{{ $beritaAcara->sekolah->nama_sekolah ?? '-' }} (Kepsek: {{ $beritaAcara->sekolah->nama_kepsek ?? '-' }})</p>
            </div>
        </div>

        @if($beritaAcara->catatan)
            <div class="px-6 md:px-8 py-4 bg-amber-50/50 border-b border-amber-100 text-xs text-slate-700">
                <b class="text-amber-800">Catatan Kegiatan:</b> {{ $beritaAcara->catatan }}
            </div>
        @endif

        <!-- Daftar Hadir Siswa Table -->
        <div class="p-6 md:p-8">
            <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center justify-between">
                <span>Daftar Siswa yang Hadir Membaca</span>
                <span class="text-xs font-semibold text-slate-500">Terverifikasi Sistem Presensi 3NF</span>
            </h4>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">No. Anggota</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Gender</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Waktu Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($beritaAcara->pengunjung as $idx => $mhs)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 text-center font-medium text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $mhs->nomor_anggota }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $mhs->nama }}</td>
                                <td class="py-3 px-4">
                                    <span class="font-bold {{ $mhs->jenis_kelamin == 'L' ? 'text-indigo-600' : 'text-pink-600' }}">
                                        {{ $mhs->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-700 font-medium">{{ $mhs->kelas }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $mhs->pivot->waktu_hadir ? \Carbon\Carbon::parse($mhs->pivot->waktu_hadir)->format('H:i:s') : 'Tercatat' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">
                                    Tidak ada data siswa terpresensi di kunjungan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

