<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $anggota->nama }}</title>
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

        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card-wrapper {
                box-shadow: none !important;
                border: 0.5px solid #94a3b8 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center justify-center font-sans">

    <!-- Action Bar -->
    <div class="mb-6 flex items-center gap-3 no-print bg-white p-3 px-5 rounded-2xl shadow-sm border border-slate-200">
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak Kartu (KTP Size)
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
            Tutup
        </button>
    </div>

    <!-- KARTU ANGGOTA STANDAR KTP (85.6 × 54 mm) -->
    <div class="print-card-wrapper ktp-card-dimension bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-900 text-white rounded-xl shadow-2xl p-2.5 flex flex-col justify-between relative overflow-hidden select-none border border-indigo-500/40">
        
        <!-- Background Pattern -->
        <div class="absolute -right-8 -bottom-8 w-28 h-28 rounded-full bg-indigo-500/10 blur-xl pointer-events-none"></div>

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
            <span class="text-[7px] font-mono font-bold px-1.5 py-0.5 rounded bg-indigo-500/25 text-indigo-300 border border-indigo-400/30">MEMBER</span>
        </div>

        <!-- Body Kartu -->
        <div class="flex gap-2.5 items-center my-auto">
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

        <!-- Footer Kartu & Barcode visual -->
        <div class="pt-1 border-t border-slate-800 flex items-center justify-between text-[6.5px] text-slate-400">
            <span>Berlaku s/d siswa aktif</span>
            <span class="font-mono text-slate-300">Kec. {{ $anggota->sekolah->kecamatan ?? 'Lampung Timur' }}</span>
        </div>
    </div>

    <p class="text-xs text-slate-400 mt-6 no-print">
        Ukuran kartu sesuai standar KTP / Kartu Pelajar (85.6 mm × 54 mm).
    </p>

</body>
</html>
