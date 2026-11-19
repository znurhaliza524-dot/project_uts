@extends('layouts.app')

@section('title', 'Data Anggota Siswa')
@section('page_title', 'Master Data Anggota Siswa')
@section('page_subtitle', 'Pencatatan keanggotaan pembaca perpustakaan keliling di setiap sekolah')

@section('content')
<style>
/* Standar Ukuran KTP / ID Card ISO/IEC 7810 ID-1: 85.60 mm x 53.98 mm */
.ktp-card-dimension {
    width: 85.6mm;
    height: 54mm;
    min-width: 85.6mm;
    min-height: 54mm;
    max-width: 85.6mm;
    max-height: 54mm;
    box-sizing: border-box;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 8mm 6mm;
    }
    body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    aside, header, footer, .no-print, #table-container-anggota, .action-bar-container {
        display: none !important;
    }
    #modal-cetak-massal {
        position: static !important;
        display: block !important;
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
        inset: auto !important;
        z-index: auto !important;
    }
    #modal-cetak-massal-backdrop {
        display: none !important;
    }
    #modal-cetak-massal .modal-header,
    #modal-cetak-massal .modal-toolbar,
    #modal-cetak-massal .modal-student-list,
    #modal-cetak-massal .modal-footer {
        display: none !important;
    }
    #modal-cetak-massal .print-container {
        display: grid !important;
        grid-template-columns: repeat(2, 85.6mm) !important;
        gap: 5mm 6mm !important;
        justify-content: center !important;
        align-content: start !important;
    }
    .ktp-print-card {
        display: flex !important;
        width: 85.6mm !important;
        height: 54mm !important;
        box-sizing: border-box !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        border: 0.5px solid #94a3b8 !important;
        border-radius: 4mm !important;
        box-shadow: none !important;
        margin: 0 !important;
    }
    .ktp-print-card.hidden-from-print {
        display: none !important;
    }
}
</style>

<div class="space-y-6">

    <!-- Filters & Action Bar -->
    <div class="action-bar-container bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <form id="form-filter-anggota" action="{{ route('anggota.index') }}" method="GET" class="w-full lg:w-auto flex flex-wrap items-center gap-2">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[220px]">
                <div class="search-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama siswa, No. Anggota, NISN, atau kelas..." autocomplete="off"
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

            <!-- Gender Filter -->
            <select name="jenis_kelamin" class="py-2 px-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Gender</option>
                <option value="L" {{ $gender == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                <option value="P" {{ $gender == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
            </select>

            <button type="button" class="btn-reset text-xs text-slate-500 hover:text-slate-800 px-2 py-1 {{ ($search || $sekolahId || $gender) ? '' : 'hidden' }}">Reset</button>
        </form>

        <div class="w-full lg:w-auto flex flex-wrap items-center gap-2">
            <!-- Tombol Buka Pop-up Cetak Massal (Tidak membuka tab/halaman baru) -->
            <button type="button" onclick="openModalCetakMassal()" class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold border border-indigo-200 transition whitespace-nowrap shadow-sm hover:shadow">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak Kartu Massal
            </button>
            <a id="btn-export-csv" href="{{ route('anggota.export.csv', ['id_sekolah' => $sekolahId, 'jenis_kelamin' => $gender]) }}" class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition whitespace-nowrap">
                <i class="fa-solid fa-file-excel mr-1.5"></i> Unduh CSV / Excel
            </a>
            <a href="{{ route('anggota.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition whitespace-nowrap">
                <i class="fa-solid fa-user-plus mr-1.5"></i> Registrasi Siswa
            </a>
        </div>
    </div>

    <!-- Table Card Container -->
    <div id="table-container-anggota" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-opacity duration-200">
        @include('anggota._table')
    </div>

</div>

<!-- ======================================================== -->
<!-- POPUP MODAL CETAK MASSAL KARTU ANGGOTA (STANDAR KTP)    -->
<!-- ======================================================== -->
<div id="modal-cetak-massal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Blur -->
    <div id="modal-cetak-massal-backdrop" onclick="closeModalCetakMassal()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-screen items-center justify-center p-3 sm:p-5 text-center">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-slate-200 my-8">
            <!-- Modal Content Container (Loaded via AJAX) -->
            <div id="modal-cetak-massal-body">
                <div class="p-16 text-center text-slate-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-indigo-600 mb-3"></i>
                    <p class="text-sm font-semibold text-slate-600">Memuat data kartu anggota siswa...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-filter-anggota');
        const resetBtn = form.querySelector('.btn-reset');
        const btnExport = document.getElementById('btn-export-csv');

        function updateExportButtons() {
            const formData = new FormData(form);
            const params = new URLSearchParams();
            for (const [k, v] of formData.entries()) {
                if (v !== '' && v !== null && v !== undefined) {
                    params.append(k, v);
                }
            }

            const sekId = formData.get('id_sekolah') || '';
            const gen = formData.get('jenis_kelamin') || '';
            const q = formData.get('search') || '';

            btnExport.href = `{{ route('anggota.export.csv') }}?${params.toString()}`;

            if (resetBtn) {
                if (q !== '' || sekId !== '' || gen !== '') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }
        }

        initAjaxTable({
            formSelector: '#form-filter-anggota',
            containerSelector: '#table-container-anggota',
            onUpdate: function() {
                updateExportButtons();
            }
        });

        form.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('change', updateExportButtons);
            el.addEventListener('input', updateExportButtons);
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModalCetakMassal();
            }
        });
    });

    // ==========================================
    // POPUP MODAL CETAK MASSAL FUNCTIONS
    // ==========================================
    function openModalCetakMassal() {
        const modal = document.getElementById('modal-cetak-massal');
        const modalBody = document.getElementById('modal-cetak-massal-body');
        const form = document.getElementById('form-filter-anggota');
        const sekId = form?.querySelector('select[name="id_sekolah"]')?.value || '';

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        modalBody.innerHTML = `
            <div class="p-16 text-center text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-indigo-600 mb-3"></i>
                <p class="text-sm font-semibold text-slate-600">Memuat data kartu anggota siswa...</p>
            </div>
        `;

        const url = `{{ route('anggota.kartu.massal') }}?id_sekolah=${encodeURIComponent(sekId)}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Gagal memuat kartu');
            return res.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(err => {
            modalBody.innerHTML = `
                <div class="p-12 text-center text-rose-500">
                    <i class="fa-solid fa-circle-exclamation text-3xl mb-2"></i>
                    <p class="text-sm font-bold">Terjadi kesalahan saat memuat kartu anggota.</p>
                    <button type="button" onclick="closeModalCetakMassal()" class="mt-4 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">Tutup</button>
                </div>
            `;
        });
    }

    function closeModalCetakMassal() {
        const modal = document.getElementById('modal-cetak-massal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Toggle individual card preview inside modal
    function modalToggleCardPreview(id) {
        const preview = document.getElementById(`modal-card-preview-${id}`);
        const item = document.getElementById(`modal-item-${id}`);
        const btn = item?.querySelector('.btn-toggle');
        const text = btn?.querySelector('.toggle-text');
        const chevron = btn?.querySelector('.chevron-icon');

        if (!preview) return;

        if (preview.classList.contains('hidden')) {
            preview.classList.remove('hidden');
            if (text) text.textContent = 'Tutup Kartu';
            if (chevron) chevron.classList.add('rotate-180');
            if (btn) {
                btn.classList.add('bg-indigo-600', 'text-white');
                btn.classList.remove('bg-slate-100', 'text-slate-600');
            }
        } else {
            preview.classList.add('hidden');
            if (text) text.textContent = 'Buka Kartu';
            if (chevron) chevron.classList.remove('rotate-180');
            if (btn) {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            }
        }
    }

    // Toggle all cards at once inside modal
    function modalToggleAllCards(open) {
        document.querySelectorAll('.modal-card-preview').forEach(p => {
            const id = p.id.replace('modal-card-preview-', '');
            const item = document.getElementById(`modal-item-${id}`);
            const btn = item?.querySelector('.btn-toggle');
            const text = btn?.querySelector('.toggle-text');
            const chevron = btn?.querySelector('.chevron-icon');

            if (open) {
                p.classList.remove('hidden');
                if (text) text.textContent = 'Tutup Kartu';
                if (chevron) chevron.classList.add('rotate-180');
                if (btn) {
                    btn.classList.add('bg-indigo-600', 'text-white');
                    btn.classList.remove('bg-slate-100', 'text-slate-600');
                }
            } else {
                p.classList.add('hidden');
                if (text) text.textContent = 'Buka Kartu';
                if (chevron) chevron.classList.remove('rotate-180');
                if (btn) {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-600');
                }
            }
        });
    }

    // Select / Deselect all checkboxes inside modal
    function modalToggleSelectAll(masterCheckbox) {
        const checked = masterCheckbox.checked;
        document.querySelectorAll('.modal-student-checkbox').forEach(cb => {
            const item = cb.closest('.modal-student-item');
            if (!item || item.style.display !== 'none') {
                cb.checked = checked;
            }
        });
        modalUpdateSelectedCount();
    }

    // Update selected student count inside modal
    function modalUpdateSelectedCount() {
        const totalSelected = document.querySelectorAll('.modal-student-checkbox:checked').length;
        const countEl = document.getElementById('modal-count-selected');
        if (countEl) countEl.textContent = totalSelected;
    }

    // Filter student list by typing name or NISN inside modal
    function modalFilterStudentList() {
        const query = document.getElementById('modal-filter-input').value.toLowerCase().trim();
        const items = document.querySelectorAll('.modal-student-item');

        items.forEach(item => {
            const name = item.querySelector('.modal-student-name')?.textContent.toLowerCase() || '';
            const nisn = item.querySelector('.modal-student-nisn')?.textContent.toLowerCase() || '';
            if (name.includes(query) || nisn.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Print only selected cards from modal
    function modalPrintSelectedCards() {
        const checkboxes = document.querySelectorAll('.modal-student-checkbox');
        let hasSelection = false;

        checkboxes.forEach(cb => {
            const id = cb.getAttribute('data-id');
            const printCard = document.getElementById(`modal-print-card-${id}`);
            if (printCard) {
                if (cb.checked) {
                    printCard.classList.remove('hidden-from-print');
                    hasSelection = true;
                } else {
                    printCard.classList.add('hidden-from-print');
                }
            }
        });

        if (!hasSelection) {
            alert('Silakan pilih minimal satu siswa untuk dicetak kartunya.');
            return;
        }

        window.print();
    }
</script>
@endpush
