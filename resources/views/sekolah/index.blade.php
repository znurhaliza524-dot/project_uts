@extends('layouts.app')

@section('title', 'Data Sekolah')
@section('page_title', 'Master Data Sekolah')
@section('page_subtitle', 'Kelola daftar sekolah tujuan layanan perpustakaan keliling di Kab. Lampung Timur')

@section('content')
<div class="space-y-6">

    <!-- Action Bar & Search -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form id="form-filter-sekolah" action="{{ route('sekolah.index') }}" method="GET" class="w-full md:w-96 flex items-center">
            <div class="relative w-full">
                <div class="search-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama sekolah, NPSN, atau kecamatan..." autocomplete="off"
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
            <button type="button" class="btn-reset ml-2 text-xs text-slate-500 hover:text-slate-700 whitespace-nowrap {{ $search ? '' : 'hidden' }}">Reset</button>
        </form>

        @if(Auth::user()?->isAdmin())
        <a href="{{ route('sekolah.create') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Sekolah Baru
        </a>
        @else
        <div class="inline-flex items-center text-xs text-slate-500 bg-slate-100 px-3 py-2 rounded-xl border border-slate-200">
            <i class="fa-solid fa-eye mr-1.5 text-slate-400"></i> Mode Lihat Petugas (Kelola Sekolah wewenang Admin)
        </div>
        @endif
    </div>

    <!-- Table Card Container -->
    <div id="table-container-sekolah" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-opacity duration-200">
        @include('sekolah._table')
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.querySelector('#form-filter-sekolah .btn-reset');
        const searchInput = document.querySelector('#form-filter-sekolah input[name="search"]');

        searchInput?.addEventListener('input', function() {
            if (resetBtn) {
                if (this.value.trim() !== '') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }
        });

        initAjaxTable({
            formSelector: '#form-filter-sekolah',
            containerSelector: '#table-container-sekolah',
            onUpdate: function() {
                if (resetBtn && searchInput) {
                    if (searchInput.value.trim() !== '') {
                        resetBtn.classList.remove('hidden');
                    } else {
                        resetBtn.classList.add('hidden');
                    }
                }
            }
        });
    });
</script>
@endpush
