@extends('layouts.app')

@section('title', 'Berita Acara Kunjungan')
@section('page_title', 'Berita Acara Layanan Keliling')
@section('page_subtitle', 'Pencatatan kegiatan operasional mobil perpustakaan keliling dan presensi siswa')

@section('content')
<div class="space-y-6">

    <!-- Filters & Action Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <form id="form-filter-ba" action="{{ route('berita-acara.index') }}" method="GET" class="w-full lg:w-auto flex flex-wrap items-center gap-2">
            <!-- Search Text -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="search-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. BA, sekolah, atau perwakilan..." autocomplete="off"
                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Sekolah Filter -->
            <select name="id_sekolah" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Sekolah</option>
                @foreach($sekolahList as $sek)
                    <option value="{{ $sek->id_sekolah }}" {{ $sekolahId == $sek->id_sekolah ? 'selected' : '' }}>
                        {{ $sek->nama_sekolah }}
                    </option>
                @endforeach
            </select>

            <!-- Rentang Tanggal -->
            <input type="date" name="tgl_awal" value="{{ $tglAwal }}" title="Tanggal Awal" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span class="text-xs text-slate-400">s/d</span>
            <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" title="Tanggal Akhir" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <button type="button" class="btn-reset text-xs text-slate-500 hover:text-slate-800 px-2 py-1 {{ ($search || $sekolahId || $tglAwal || $tglAkhir) ? '' : 'hidden' }}">Reset</button>
        </form>

        <a href="{{ route('berita-acara.create') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition whitespace-nowrap">
            <i class="fa-solid fa-plus mr-2"></i> Buat Berita Acara Baru
        </a>
    </div>

    <!-- Table Card Container -->
    <div id="table-container-ba" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-opacity duration-200">
        @include('berita_acara._table')
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-filter-ba');
        const resetBtn = form.querySelector('.btn-reset');

        function checkResetVisibility() {
            const formData = new FormData(form);
            const q = formData.get('search') || '';
            const sek = formData.get('id_sekolah') || '';
            const t1 = formData.get('tgl_awal') || '';
            const t2 = formData.get('tgl_akhir') || '';

            if (resetBtn) {
                if (q !== '' || sek !== '' || t1 !== '' || t2 !== '') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }
        }

        initAjaxTable({
            formSelector: '#form-filter-ba',
            containerSelector: '#table-container-ba',
            onUpdate: function() {
                checkResetVisibility();
            }
        });

        form.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('change', checkResetVisibility);
            el.addEventListener('input', checkResetVisibility);
        });
    });
</script>
@endpush
