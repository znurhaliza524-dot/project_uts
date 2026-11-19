<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Anggota - {{ $sekolah->nama_sekolah ?? 'Semua Sekolah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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

        @media screen {
            /* Tampilan layar dalam piksel yang proporsional dengan KTP */
            .ktp-screen-preview {
                width: 324px;
                height: 204px;
            }
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
                font-size: 8pt;
            }
            .no-print {
                display: none !important;
            }
            /* Grid cetak lembar A4: 2 kolom kartu KTP */
            .print-container {
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
</head>
<body class="bg-slate-100 min-h-screen font-sans text-slate-800">

    <!-- ========================================== -->
    <!-- SCREEN VIEW (TAMPILAN DAFTAR NAMA & NISN)  -->
    <!-- ========================================== -->
    <div class="no-print p-4 md:p-8 max-w-5xl mx-auto space-y-6">

        <!-- Top Header & Action Bar -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <i class="fa-solid fa-id-card mr-1.5"></i> Standar KTP (85.6 × 54 mm)
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Cetak Massal</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 leading-tight">Daftar Cetak Kartu Anggota Siswa</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Total: <b id="count-total">{{ $anggotaList->count() }}</b> siswa 
                    @if($sekolah) &bull; Sekolah: <b>{{ $sekolah->nama_sekolah }}</b> @endif
                    @if($kelas) &bull; Kelas: <b>{{ $kelas }}</b> @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <button type="button" onclick="printSelectedCards()" class="flex-1 md:flex-none px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center">
                    <i class="fa-solid fa-print mr-2"></i> Cetak Kartu Terpilih (<span id="count-selected">{{ $anggotaList->count() }}</span>)
                </button>
                <button type="button" onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Toolbar: Search & Toggle Controls -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer select-none">
                    <input type="checkbox" id="check-all" checked onchange="toggleSelectAll(this)" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <span>Pilih Semua Siswa</span>
                </label>
                <span class="text-slate-300">|</span>
                <button type="button" onclick="toggleAllCards(true)" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                    <i class="fa-solid fa-angles-down mr-1"></i> Buka Semua Kartu
                </button>
                <span class="text-slate-300">|</span>
                <button type="button" onclick="toggleAllCards(false)" class="text-xs text-slate-500 hover:text-slate-700 font-semibold">
                    <i class="fa-solid fa-angles-up mr-1"></i> Tutup Semua
                </button>
            </div>

            <!-- Client-side quick filter -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" id="filter-input" onkeyup="filterStudentList()" placeholder="Saring nama atau NISN..."
                    class="w-full pl-9 pr-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <!-- Student List Items (Accordion / Expandable) -->
        <div class="space-y-3" id="student-list-container">
            @forelse($anggotaList as $index => $anggota)
                <div class="student-item bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition" id="item-{{ $anggota->id_anggota }}">
                    
                    <!-- Row Header (Nama & NISN) -->
                    <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition" onclick="toggleCardPreview({{ $anggota->id_anggota }})">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Checkbox print -->
                            <input type="checkbox" class="student-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer"
                                data-id="{{ $anggota->id_anggota }}" checked onclick="event.stopPropagation(); updateSelectedCount();">
                            
                            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ $index + 1 }}
                            </span>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 truncate hover:text-indigo-600 student-name">
                                        {{ $anggota->nama }}
                                    </h4>
                                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-md font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 student-nisn">
                                        NISN: {{ $anggota->nisn ?: '-' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                    No. Anggota: <span class="font-mono font-medium text-slate-700">{{ $anggota->nomor_anggota }}</span> &bull; 
                                    Kelas: <span class="font-medium text-slate-700">{{ $anggota->kelas }}</span> &bull; 
                                    Sekolah: <span class="font-medium text-slate-700">{{ $anggota->sekolah->nama_sekolah ?? '-' }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Action Toggle Button -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" class="btn-toggle px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 text-xs font-semibold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span class="toggle-text">Buka Kartu</span>
                                <i class="fa-solid fa-chevron-down text-[10px] chevron-icon transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Collapsible Card Preview (Ukuran Standar KTP) -->
                    <div class="card-preview hidden border-t border-slate-100 p-6 bg-slate-50 flex flex-col items-center justify-center" id="card-preview-{{ $anggota->id_anggota }}">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-id-card text-indigo-500 mr-1"></i> Pratinjau Kartu Ukuran KTP (85.6 × 54 mm)
                        </p>

                        <!-- STANDAR KTP CARD PREVIEW -->
                        <div class="ktp-card-dimension bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-900 text-white rounded-xl shadow-xl border border-indigo-500/40 p-2.5 flex flex-col justify-between relative overflow-hidden select-none">
                            
                            <!-- Header Kartu -->
                            <div class="flex items-center justify-between pb-1.5 border-b border-indigo-500/20">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-md bg-amber-400 text-slate-950 flex items-center justify-center font-black text-[10px] shadow">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-[8.5px] font-black tracking-wider text-amber-300 uppercase leading-none">PERPUSTAKAAN KELILING</h5>
                                        <p class="text-[6.5px] text-slate-300 leading-tight">Dispusip Lampung Timur</p>
                                    </div>
                                </div>
                                <span class="text-[7px] font-mono font-bold px-1 py-0.5 rounded bg-indigo-500/25 text-indigo-300 border border-indigo-400/30">MEMBER</span>
                            </div>

                            <!-- Body Kartu -->
                            <div class="flex gap-2 items-center my-auto">
                                <!-- Foto Frame -->
                                <div class="w-11 h-14 rounded-lg bg-slate-800 border border-amber-400/40 flex flex-col items-center justify-center text-slate-400 flex-shrink-0 shadow-inner">
                                    <i class="fa-solid fa-user-graduate text-base mb-0.5 text-slate-400"></i>
                                    <span class="text-[6.5px] font-mono text-slate-300">{{ $anggota->kelas }}</span>
                                </div>

                                <!-- Detail Siswa -->
                                <div class="flex-1 min-w-0 space-y-0.5">
                                    <div>
                                        <span class="text-[6px] text-slate-400 uppercase tracking-wider block">Nama Anggota</span>
                                        <h4 class="text-[9.5px] font-black text-white truncate leading-tight">{{ $anggota->nama }}</h4>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 text-[7px]">
                                        <div>
                                            <span class="text-[6px] text-slate-400 block">No. Anggota</span>
                                            <span class="font-mono font-bold text-amber-300 truncate block">{{ $anggota->nomor_anggota }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[6px] text-slate-400 block">NISN</span>
                                            <span class="font-mono text-slate-200 block">{{ $anggota->nisn ?: '-' }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-[6px] text-slate-400 block">Sekolah</span>
                                        <p class="text-[7.5px] font-bold text-slate-200 truncate leading-tight">{{ $anggota->sekolah->nama_sekolah ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Kartu -->
                            <div class="pt-1 border-t border-slate-800 flex items-center justify-between text-[6.5px] text-slate-400">
                                <span>Berlaku s/d siswa aktif</span>
                                <span class="font-mono text-slate-300">Kec. {{ $anggota->sekolah->kecamatan ?? 'Lampung Timur' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                    <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                    <p>Tidak ada data anggota siswa ditemukan.</p>
                </div>
            @endforelse
        </div>

    </div>


    <!-- ========================================== -->
    <!-- PRINT VIEW (HANYA MUNCUL SAAT CETAK / PDF) -->
    <!-- ========================================== -->
    <div class="hidden print:grid print-container">
        @foreach($anggotaList as $anggota)
            <div class="ktp-print-card ktp-card-dimension bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-900 text-white rounded-xl p-2.5 flex flex-col justify-between relative overflow-hidden" id="print-card-{{ $anggota->id_anggota }}">
                
                <!-- Header Kartu -->
                <div class="flex items-center justify-between pb-1.5 border-b border-indigo-500/20">
                    <div class="flex items-center gap-1.5">
                        <div class="w-5 h-5 rounded-md bg-amber-400 text-slate-950 flex items-center justify-center font-black text-[10px] shadow">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <h5 class="text-[8.5px] font-black tracking-wider text-amber-300 uppercase leading-none">PERPUSTAKAAN KELILING</h5>
                            <p class="text-[6.5px] text-slate-300 leading-tight">Dispusip Lampung Timur</p>
                        </div>
                    </div>
                    <span class="text-[7px] font-mono font-bold px-1 py-0.5 rounded bg-indigo-500/25 text-indigo-300 border border-indigo-400/30">MEMBER</span>
                </div>

                <!-- Body Kartu -->
                <div class="flex gap-2 items-center my-auto">
                    <!-- Foto Frame -->
                    <div class="w-11 h-14 rounded-lg bg-slate-800 border border-amber-400/40 flex flex-col items-center justify-center text-slate-400 flex-shrink-0">
                        <i class="fa-solid fa-user-graduate text-base mb-0.5 text-slate-400"></i>
                        <span class="text-[6.5px] font-mono text-slate-300">{{ $anggota->kelas }}</span>
                    </div>

                    <!-- Detail Siswa -->
                    <div class="flex-1 min-w-0 space-y-0.5">
                        <div>
                            <span class="text-[6px] text-slate-400 uppercase tracking-wider block">Nama Anggota</span>
                            <h4 class="text-[9.5px] font-black text-white truncate leading-tight">{{ $anggota->nama }}</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-1 text-[7px]">
                            <div>
                                <span class="text-[6px] text-slate-400 block">No. Anggota</span>
                                <span class="font-mono font-bold text-amber-300 truncate block">{{ $anggota->nomor_anggota }}</span>
                            </div>
                            <div>
                                <span class="text-[6px] text-slate-400 block">NISN</span>
                                <span class="font-mono text-slate-200 block">{{ $anggota->nisn ?: '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[6px] text-slate-400 block">Sekolah</span>
                            <p class="text-[7.5px] font-bold text-slate-200 truncate leading-tight">{{ $anggota->sekolah->nama_sekolah ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Kartu -->
                <div class="pt-1 border-t border-slate-800 flex items-center justify-between text-[6.5px] text-slate-400">
                    <span>Berlaku s/d siswa aktif</span>
                    <span class="font-mono text-slate-300">Kec. {{ $anggota->sekolah->kecamatan ?? 'Lampung Timur' }}</span>
                </div>
            </div>
        @endforeach
    </div>


    <!-- ========================================== -->
    <!-- INTERACTIVE JAVASCRIPT                     -->
    <!-- ========================================== -->
    <script>
        // Toggle individual card preview
        function toggleCardPreview(id) {
            const preview = document.getElementById(`card-preview-${id}`);
            const item = document.getElementById(`item-${id}`);
            const btn = item.querySelector('.btn-toggle');
            const text = btn.querySelector('.toggle-text');
            const chevron = btn.querySelector('.chevron-icon');

            if (preview.classList.contains('hidden')) {
                preview.classList.remove('hidden');
                text.textContent = 'Tutup Kartu';
                chevron.classList.add('rotate-180');
                btn.classList.add('bg-indigo-600', 'text-white');
                btn.classList.remove('bg-slate-100', 'text-slate-600');
            } else {
                preview.classList.add('hidden');
                text.textContent = 'Buka Kartu';
                chevron.classList.remove('rotate-180');
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            }
        }

        // Toggle all cards at once
        function toggleAllCards(open) {
            document.querySelectorAll('.card-preview').forEach(p => {
                const id = p.id.replace('card-preview-', '');
                const item = document.getElementById(`item-${id}`);
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

        // Select / Deselect all checkboxes
        function toggleSelectAll(masterCheckbox) {
            const checked = masterCheckbox.checked;
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                // Only toggle visible items if filtered
                const item = cb.closest('.student-item');
                if (!item || item.style.display !== 'none') {
                    cb.checked = checked;
                }
            });
            updateSelectedCount();
        }

        // Update selected student count
        function updateSelectedCount() {
            const totalSelected = document.querySelectorAll('.student-checkbox:checked').length;
            const countEl = document.getElementById('count-selected');
            if (countEl) countEl.textContent = totalSelected;
        }

        // Filter student list by typing name or NISN
        function filterStudentList() {
            const query = document.getElementById('filter-input').value.toLowerCase().trim();
            const items = document.querySelectorAll('.student-item');

            items.forEach(item => {
                const name = item.querySelector('.student-name')?.textContent.toLowerCase() || '';
                const nisn = item.querySelector('.student-nisn')?.textContent.toLowerCase() || '';
                if (name.includes(query) || nisn.includes(query)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Print only selected cards
        function printSelectedCards() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            let hasSelection = false;

            checkboxes.forEach(cb => {
                const id = cb.getAttribute('data-id');
                const printCard = document.getElementById(`print-card-${id}`);
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

</body>
</html>
