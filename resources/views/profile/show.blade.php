@extends('layouts.app')

@section('title', 'Profil & Akun Pengguna')
@section('page_title', 'Profil & Keamanan Akun')
@section('page_subtitle', 'Kelola informasi identitas petugas dan keamanan kata sandi akun sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left: User Profile Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 text-center flex flex-col items-center">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-400 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-indigo-500/25 mb-4 ring-4 ring-indigo-500/10">
                {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
            </div>
            
            <h3 class="text-base font-bold text-slate-900">{{ Auth::user()->pustakawan->nama ?? Auth::user()->username }}</h3>
            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ Auth::user()->username }}</p>
            
            <span class="mt-3 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                {{ Auth::user()->role }}
            </span>

            <div class="w-full mt-6 pt-5 border-t border-slate-100 text-left text-xs space-y-2">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">NIP Petugas</span>
                    <span class="font-mono font-medium text-slate-800">{{ Auth::user()->pustakawan->nip ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Jabatan</span>
                    <span class="font-medium text-slate-800">{{ Auth::user()->pustakawan->jabatan ?? 'Petugas' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Status Akun</span>
                    <span class="font-bold text-emerald-600">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Right: Change Password Form -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
            <h4 class="text-base font-bold text-slate-900 mb-1">Ganti Kata Sandi (Password)</h4>
            <p class="text-xs text-slate-500 mb-6">Pastikan akun Anda menggunakan kata sandi yang aman untuk mencegah akses tidak sah.</p>

            @if(isset($errors) && $errors->any())
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <p class="font-bold mb-1">Terjadi kesalahan input:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                    <input type="password" name="current_password" required placeholder="Masukkan kata sandi lama Anda"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center">
                        <i class="fa-solid fa-key mr-2"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection

