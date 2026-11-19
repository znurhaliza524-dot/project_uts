<!-- Modal Header -->
<div class="modal-header p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50 rounded-t-3xl">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                <i class="fa-solid fa-id-card mr-1.5"></i> Standar KTP (85.6 × 54 mm)
            </span>
            <span class="text-xs text-slate-400 font-medium">&bull; Pop-up Cetak Massal</span>
        </div>
        <h3 class="text-base font-bold text-slate-900">Daftar Cetak Kartu Anggota Siswa</h3>
        <p class="text-xs text-slate-500 mt-0.5">
            Total: <b>{{ $anggotaList->count() }} siswa</b> 
            @if($sekolah) &bull; Sekolah: <b>{{ $sekolah->nama_sekolah }}</b> @endif
            @if($kelas) &bull; Kelas: <b>{{ $kelas }}</b> @endif
        </p>
    </div>
    <button type="button" onclick="closeModalCetakMassal()" class="w-9 h-9 rounded-xl bg-slate-200/70 hover:bg-slate-300 text-slate-600 flex items-center justify-center transition">
        <i class="fa-solid fa-xmark text-sm"></i>
    </button>
</div>

<!-- Modal Toolbar -->
<div class="modal-toolbar p-4 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer select-none">
            <input type="checkbox" id="modal-check-all" checked onchange="modalToggleSelectAll(this)" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
            <span>Pilih Semua Siswa</span>
        </label>
        <span class="text-slate-300">|</span>
        <button type="button" onclick="modalToggleAllCards(true)" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
            <i class="fa-solid fa-angles-down mr-1"></i> Buka Semua
        </button>
        <span class="text-slate-300">|</span>
        <button type="button" onclick="modalToggleAllCards(false)" class="text-xs text-slate-500 hover:text-slate-700 font-semibold">
            <i class="fa-solid fa-angles-up mr-1"></i> Tutup Semua
        </button>
    </div>

    <!-- Client-side quick filter -->
    <div class="relative w-full sm:w-64">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </div>
        <input type="text" id="modal-filter-input" onkeyup="modalFilterStudentList()" placeholder="Cari nama atau NISN..."
            class="w-full pl-9 pr-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
    </div>
</div>

<!-- Modal Body: Student List Items -->
<div class="modal-student-list p-4 md:p-6 space-y-3 overflow-y-auto max-h-[55vh] bg-slate-50/70" id="modal-student-list-container">
    @forelse($anggotaList as $index => $anggota)
        <div class="modal-student-item bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition" id="modal-item-{{ $anggota->id_anggota }}">
            
            <!-- Row Header (Nama & NISN) -->
            <div class="p-3.5 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition" onclick="modalToggleCardPreview({{ $anggota->id_anggota }})">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Checkbox print -->
                    <input type="checkbox" class="modal-student-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer"
                        data-id="{{ $anggota->id_anggota }}" checked onclick="event.stopPropagation(); modalUpdateSelectedCount();">
                    
                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center flex-shrink-0">
                        {{ $index + 1 }}
                    </span>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs md:text-sm font-bold text-slate-900 truncate hover:text-indigo-600 modal-student-name">
                                {{ $anggota->nama }}
                            </h4>
                            <span class="font-mono text-[10px] px-2 py-0.5 rounded-md font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 modal-student-nisn">
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
            <div class="modal-card-preview hidden border-t border-slate-100 p-5 bg-slate-50 flex flex-col items-center justify-center" id="modal-card-preview-{{ $anggota->id_anggota }}">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">
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
                            <span class="text-[6.5px] font-mono text-slate-300 font-bold">{{ $anggota->kelas }}</span>
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
        <div class="py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
            <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
            <p>Tidak ada data anggota siswa ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Modal Footer -->
<div class="modal-footer p-4 md:p-5 border-t border-slate-200 bg-white rounded-b-3xl flex flex-col sm:flex-row items-center justify-between gap-3">
    <div class="text-xs text-slate-500 text-center sm:text-left">
        Terpilih: <b id="modal-count-selected" class="text-indigo-600 font-bold">{{ $anggotaList->count() }}</b> dari <b>{{ $anggotaList->count() }}</b> siswa
    </div>
    <div class="flex items-center gap-2 w-full sm:w-auto">
        <button type="button" onclick="closeModalCetakMassal()" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
            Tutup
        </button>
        <button type="button" onclick="modalPrintSelectedCards()" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak Kartu Terpilih (KTP Size)
        </button>
    </div>
</div>

<!-- Hidden Print Container for print execution -->
<div class="hidden print:grid print-container" id="modal-print-container">
    @foreach($anggotaList as $anggota)
        <div class="ktp-print-card ktp-card-dimension bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-900 text-white rounded-xl p-2.5 flex flex-col justify-between relative overflow-hidden" id="modal-print-card-{{ $anggota->id_anggota }}">
            
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
                    <span class="text-[6.5px] font-mono text-slate-300 font-bold">{{ $anggota->kelas }}</span>
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

