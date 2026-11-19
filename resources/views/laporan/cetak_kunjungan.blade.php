<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kunjungan Perpustakaan Keliling - {{ $tahun ?: 'Semua' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
            @page { size: A4 landscape; margin: 15mm; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center">

    <!-- Action Bar -->
    <div class="mb-6 flex items-center gap-3 no-print">
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-sans font-bold text-xs shadow-md transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/></svg>
            Cetak Laporan Kunjungan (Print / PDF Landscape)
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-sans font-semibold text-xs transition">
            Tutup
        </button>
    </div>

    <!-- Official Document Canvas (Landscape) -->
    <div class="w-full max-w-5xl bg-white p-8 md:p-12 shadow-2xl border border-slate-300">
        
        <!-- Kop Surat -->
        <div class="text-center border-b-4 border-double border-black pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide">PEMERINTAH KABUPATEN LAMPUNG TIMUR</h3>
            <h2 class="text-xl font-extrabold uppercase tracking-wider">DINAS PERPUSTAKAAN DAN KEARSIPAN</h2>
            <p class="text-xs italic mt-0.5">Komplek Perkantoran Pemerintah Daerah Kabupaten Lampung Timur, Sukadana</p>
            <p class="text-xs">Email: perpustakaan.lampungtimur@gmail.com &bull; Kode Pos 34194</p>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center my-6">
            <h4 class="text-base font-bold underline uppercase tracking-wide">REKAPITULASI PELAYANAN OPERASIONAL PERPUSTAKAAN KELILING</h4>
            <p class="text-sm font-bold mt-1">
                @if($bulan) BULAN {{ strtoupper(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan - 1]) }} @endif
                TAHUN {{ $tahun ?: date('Y') }}
            </p>
            @if($sekolah)
                <p class="text-xs font-semibold mt-0.5">Sekolah: {{ $sekolah->nama_sekolah }} (Kec. {{ $sekolah->kecamatan }})</p>
            @endif
        </div>

        <!-- Tabel Rekapitulasi -->
        <table class="w-full text-xs border-collapse border border-black my-4">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-black py-2 px-2 w-10 text-center">No</th>
                    <th class="border border-black py-2 px-3 text-center">Tanggal</th>
                    <th class="border border-black py-2 px-3 text-center">Nomor Berita Acara</th>
                    <th class="border border-black py-2 px-3 text-left">Sekolah Tujuan</th>
                    <th class="border border-black py-2 px-3 text-left">Petugas Pustakawan</th>
                    <th class="border border-black py-2 px-3 text-left">Perwakilan Sekolah</th>
                    <th class="border border-black py-2 px-3 text-center">Jml Pengunjung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beritaAcara as $i => $ba)
                    <tr>
                        <td class="border border-black py-1.5 px-2 text-center">{{ $i + 1 }}</td>
                        <td class="border border-black py-1.5 px-3 text-center">{{ \Carbon\Carbon::parse($ba->tgl_kunjungan)->format('d/m/Y') }}</td>
                        <td class="border border-black py-1.5 px-3 font-mono text-center font-bold">{{ $ba->nomor_ba }}</td>
                        <td class="border border-black py-1.5 px-3 font-semibold">{{ $ba->sekolah->nama_sekolah ?? '-' }}</td>
                        <td class="border border-black py-1.5 px-3">{{ $ba->pustakawan->nama ?? '-' }}</td>
                        <td class="border border-black py-1.5 px-3">{{ $ba->nama_perwakilan_sekolah }}</td>
                        <td class="border border-black py-1.5 px-3 text-center font-bold">{{ $ba->detail_kunjungan_count }} siswa</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-black py-4 text-center italic">Tidak ada catatan kunjungan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold">
                    <td colspan="3" class="border border-black py-2 px-3 text-right uppercase">Total Operasional :</td>
                    <td colspan="3" class="border border-black py-2 px-3 font-bold">{{ $totalKunjungan }} Kali Kunjungan Mobil</td>
                    <td class="border border-black py-2 px-3 text-center text-sm font-extrabold">{{ $totalPengunjung }} orang</td>
                </tr>
            </tfoot>
        </table>

        <!-- Tanda Tangan -->
        <div class="mt-12 pt-6 grid grid-cols-2 text-center text-sm">
            <div></div>
            <div>
                <p>Sukadana, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p>Kepala Dinas Perpustakaan dan Kearsipan<br>Kabupaten Lampung Timur</p>
                <div class="h-24"></div>
                <p class="font-bold underline">Drs. MULYANDA</p>
                <p class="text-xs">Pembina Utama Muda</p>
                <p class="text-xs">NIP. 19650412 199203 1 004</p>
            </div>
        </div>

    </div>

</body>
</html>

