@extends('layouts.app')

@section('title', 'Edit Data Anggota')
@section('page_title', 'Edit Data Anggota Siswa')
@section('page_subtitle', 'Perbarui data siswa ' . $anggota->nama)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
        
        <div class="flex items-center justify-between pb-5 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Data Anggota Siswa</h3>
                <p class="text-xs text-slate-500">ID Anggota: #{{ $anggota->id_anggota }} &bull; {{ $anggota->nomor_anggota }}</p>
            </div>
            <a href="{{ route('anggota.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
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

        <form action="{{ route('anggota.update', $anggota->id_anggota) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Sekolah Asal <span class="text-rose-500">*</span></label>
                    <select name="id_sekolah" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        @foreach($sekolahList as $sek)
                            <option value="{{ $sek->id_sekolah }}" {{ old('id_sekolah', $anggota->id_sekolah) == $sek->id_sekolah ? 'selected' : '' }}>
                                {{ $sek->nama_sekolah }} (Kec. {{ $sek->kecamatan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Kartu Anggota <span class="text-rose-500">*</span></label>
                    <input type="text" name="nomor_anggota" value="{{ old('nomor_anggota', $anggota->nomor_anggota) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $anggota->nama) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $anggota->nisn) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="kelas" value="{{ old('kelas', $anggota->kelas) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Anggota <span class="text-rose-500">*</span></label>
                    <select name="status_anggota" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        <option value="aktif" {{ old('status_anggota', $anggota->status_anggota) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status_anggota', $anggota->status_anggota) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="pindah" {{ old('status_anggota', $anggota->status_anggota) == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Domisili Siswa</label>
                <textarea name="alamat" rows="2"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('alamat', $anggota->alamat) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('anggota.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Perbarui Anggota
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

