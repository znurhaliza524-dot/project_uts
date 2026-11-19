<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tahunan Anggota Perpustakaan Keliling - {{ $tahun }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
            @page { size: A4 portrait; margin: 15mm; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center">

    <!-- Action Bar -->
    <div class="mb-6 flex items-center gap-3 no-print">
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-sans font-bold text-xs shadow-md transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/></svg>
            Cetak Laporan Tahunan (PDF / Print)
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-sans font-semibold text-xs transition">
            Tutup
        </button>
    </div>

    <!-- Official Document Canvas -->
    <div class="w-full max-w-4xl bg-white p-8 md:p-12 shadow-2xl border border-slate-300">
        
        <!-- Kop Surat -->
        <div class="text-center border-b-4 border-double border-black pb-4 mb-6">
            <h3 class="text-base font-bold uppercase tracking-wide">PEMERINTAH KABUPATEN LAMPUNG TIMUR</h3>
            <h2 class="text-xl font-extrabold uppercase tracking-wider">DINAS PERPUSTAKAAN DAN KEARSIPAN</h2>
            <p class="text-xs italic mt-0.5">Komplek Perkantoran Pemerintah Daerah Kabupaten Lampung Timur, Sukadana</p>
            <p class="text-xs">Email: perpustakaan.lampungtimur@gmail.com &bull; Kode Pos 34194</p>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center my-6">
            <h4 class="text-base font-bold underline uppercase tracking-wide">LAPORAN DATA ANGGOTA PERPUSTAKAAN KELILING</h4>
            <p class="text-sm font-bold mt-1">PERIODE TAHUN {{ $tahun }}</p>
            @if($sekolah)
                <p class="text-xs font-semibold mt-0.5">Sekolah: {{ $sekolah->nama_sekolah }} (Kec. {{ $sekolah->kecamatan }})</p>
            @endif
        </div>

        <!-- Tabel Rekapitulasi -->
        <table class="w-full text-xs border-collapse border border-black my-4">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-black py-2 px-2 w-10 text-center">No</th>
                    <th class="border border-black py-2 px-3 text-left">Nama Sekolah Binaan</th>
                    <th class="border border-black py-2 px-3 text-left">Kecamatan</th>
                    <th class="border border-black py-2 px-2 text-center">L</th>
                    <th class="border border-black py-2 px-2 text-center">P</th>
                    <th class="border border-black py-2 px-3 text-center">Jml Anggota</th>
                    <th class="border border-black py-2 px-3 text-center">Jml Kunjungan Mobil</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; $totalL = 0; $totalP = 0; $totalKunjungan = 0; @endphp
                @forelse($rekapSekolah as $idx => $row)
                    @php 
                        $grandTotal += $row->total_anggota;
                        $totalL += $row->anggota_laki;
                        $totalP += $row->anggota_perempuan;
                        $totalKunjungan += $row->total_kunjungan;
                    @endphp
                    <tr>
                        <td class="border border-black py-1.5 px-2 text-center">{{ $idx + 1 }}</td>
                        <td class="border border-black py-1.5 px-3 font-semibold">{{ $row->nama_sekolah }}</td>
                        <td class="border border-black py-1.5 px-3">{{ $row->kecamatan }}</td>
                        <td class="border border-black py-1.5 px-2 text-center">{{ $row->anggota_laki }}</td>
                        <td class="border border-black py-1.5 px-2 text-center">{{ $row->anggota_perempuan }}</td>
                        <td class="border border-black py-1.5 px-3 text-center font-bold">{{ $row->total_anggota }}</td>
                        <td class="border border-black py-1.5 px-3 text-center">{{ $row->total_kunjungan }} kali</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-black py-4 text-center italic">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold">
                    <td colspan="3" class="border border-black py-2 px-3 text-right uppercase">Total Keseluruhan :</td>
                    <td class="border border-black py-2 px-2 text-center">{{ $totalL }}</td>
                    <td class="border border-black py-2 px-2 text-center">{{ $totalP }}</td>
                    <td class="border border-black py-2 px-3 text-center text-sm">{{ $grandTotal }} orang</td>
                    <td class="border border-black py-2 px-3 text-center">{{ $totalKunjungan }} kali</td>
                </tr>
            </tfoot>
        </table>

        <!-- Jika Cetak untuk Sekolah Spesifik, Lampirkan Siswa -->
        @if($anggotaList && $anggotaList->count() > 0)
            <div class="mt-8 pt-4">
                <h5 class="text-xs font-bold uppercase underline mb-2">Lampiran Daftar Nama Anggota Siswa ({{ $sekolah->nama_sekolah }}):</h5>
                <table class="w-full text-xs border-collapse border border-black">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="border border-black py-1.5 px-2 w-8 text-center">No</th>
                            <th class="border border-black py-1.5 px-3 text-center">No. Anggota</th>
                            <th class="border border-black py-1.5 px-3 text-left">Nama Siswa</th>
                            <th class="border border-black py-1.5 px-2 text-center">L/P</th>
                            <th class="border border-black py-1.5 px-2 text-center">Kelas</th>
                            <th class="border border-black py-1.5 px-3 text-left">Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggotaList as $no => $ag)
                            <tr>
                                <td class="border border-black py-1 px-2 text-center">{{ $no + 1 }}</td>
                                <td class="border border-black py-1 px-3 text-center font-mono">{{ $ag->nomor_anggota }}</td>
                                <td class="border border-black py-1 px-3 font-semibold">{{ $ag->nama }}</td>
                                <td class="border border-black py-1 px-2 text-center">{{ $ag->jenis_kelamin }}</td>
                                <td class="border border-black py-1 px-2 text-center">{{ $ag->kelas }}</td>
                                <td class="border border-black py-1 px-3">{{ $ag->alamat ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Tanda Tangan Mengetahui Kepala Dinas -->
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

