@extends('layouts.app')

@section('title', 'Edit Berita Acara')
@section('page_title', 'Edit Berita Acara & Presensi')
@section('page_subtitle', 'Perbarui data kunjungan ' . $beritaAcara->nomor_ba)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
        
        <div class="flex items-center justify-between pb-5 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Berita Acara</h3>
                <p class="text-xs text-slate-500">ID Berita Acara: #{{ $beritaAcara->id_ba }}</p>
            </div>
            <a href="{{ route('berita-acara.show', $beritaAcara->id_ba) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <p class="font-bold mb-1">Periksa kembali isian form:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('berita-acara.update', $beritaAcara->id_ba) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1 -->
            <div>
                <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-3">1. Identitas Berita Acara</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Berita Acara <span class="text-rose-500">*</span></label>
                        <input type="text" name="nomor_ba" value="{{ old('nomor_ba', $beritaAcara->nomor_ba) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_kunjungan" value="{{ old('tgl_kunjungan', $beritaAcara->tgl_kunjungan->format('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $beritaAcara->waktu_mulai ? substr($beritaAcara->waktu_mulai, 0, 5) : '') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $beritaAcara->waktu_selesai ? substr($beritaAcara->waktu_selesai, 0, 5) : '') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-3">2. Pihak Terkait</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Sekolah Tujuan <span class="text-rose-500">*</span></label>
                        <select name="id_sekolah" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            @foreach($sekolahList as $sek)
                                <option value="{{ $sek->id_sekolah }}" {{ old('id_sekolah', $beritaAcara->id_sekolah) == $sek->id_sekolah ? 'selected' : '' }}>
                                    {{ $sek->nama_sekolah }} (Kec. {{ $sek->kecamatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Petugas Pustakawan <span class="text-rose-500">*</span></label>
                        <select name="id_pustakawan" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            @foreach($pustakawanList as $pus)
                                <option value="{{ $pus->id_pustakawan }}" {{ old('id_pustakawan', $beritaAcara->id_pustakawan) == $pus->id_pustakawan ? 'selected' : '' }}>
                                    {{ $pus->nama }} (NIP: {{ $pus->nip }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Perwakilan Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_perwakilan_sekolah" value="{{ old('nama_perwakilan_sekolah', $beritaAcara->nama_perwakilan_sekolah) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP Perwakilan Sekolah</label>
                        <input type="text" name="nip_perwakilan_sekolah" value="{{ old('nip_perwakilan_sekolah', $beritaAcara->nip_perwakilan_sekolah) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('catatan', $beritaAcara->catatan) }}</textarea>
                </div>
            </div>

            <!-- Section 3: Presensi -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-3">3. Presensi Kehadiran Siswa</h4>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto pr-1">
                        @foreach($anggotaList as $item)
                            <label class="flex items-center p-2.5 rounded-xl bg-white border border-slate-200/80 hover:border-indigo-400 cursor-pointer transition select-none">
                                <input type="checkbox" name="anggota_hadir[]" value="{{ $item->id_anggota }}" 
                                    {{ in_array($item->id_anggota, old('anggota_hadir', $hadirIds)) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $item->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $item->nomor_anggota }} &bull; Kelas: {{ $item->kelas }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('berita-acara.show', $beritaAcara->id_ba) }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Perbarui Berita Acara
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

