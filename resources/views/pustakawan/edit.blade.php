@extends('layouts.app')

@section('title', 'Edit Pustakawan')
@section('page_title', 'Edit Data Pustakawan')
@section('page_subtitle', 'Perbarui data kepegawaian petugas ' . $pustakawan->nama)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
        
        <div class="flex items-center justify-between pb-5 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Data Pustakawan</h3>
                <p class="text-xs text-slate-500">ID Petugas: #{{ $pustakawan->id_pustakawan }}</p>
            </div>
            <a href="{{ route('pustakawan.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
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

        <form action="{{ route('pustakawan.update', $pustakawan->id_pustakawan) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $pustakawan->nama) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nip" value="{{ old('nip', $pustakawan->nip) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $pustakawan->jabatan) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">No. Telepon / WhatsApp</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $pustakawan->no_telp) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('pustakawan.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Perbarui Data
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

