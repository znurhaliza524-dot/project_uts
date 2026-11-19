@extends('layouts.app')

@section('title', 'Data Pustakawan')
@section('page_title', 'Master Data Pustakawan')
@section('page_subtitle', 'Kelola data petugas dinas yang mengoperasikan armada perpustakaan keliling')

@section('content')
<div class="space-y-6">

    <!-- Action Bar & Search -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form id="form-filter-pustakawan" action="{{ route('pustakawan.index') }}" method="GET" class="w-full md:w-96 flex items-center">
            <div class="relative w-full">
                <div class="search-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau jabatan..." autocomplete="off"
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
            <button type="button" class="btn-reset ml-2 text-xs text-slate-500 hover:text-slate-700 whitespace-nowrap {{ $search ? '' : 'hidden' }}">Reset</button>
        </form>

        <a href="{{ route('pustakawan.create') }}" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
            <i class="fa-solid fa-user-plus mr-2"></i> Tambah Pustakawan Baru
        </a>
    </div>

    <!-- Table Card Container -->
    <div id="table-container-pustakawan" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-opacity duration-200">
        @include('pustakawan._table')
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.querySelector('#form-filter-pustakawan .btn-reset');
        const searchInput = document.querySelector('#form-filter-pustakawan input[name="search"]');

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
            formSelector: '#form-filter-pustakawan',
            containerSelector: '#table-container-pustakawan',
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
