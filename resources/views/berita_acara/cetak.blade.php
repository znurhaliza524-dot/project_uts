<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Berita Acara - {{ $beritaAcara->nomor_ba }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
            @page { size: A4; margin: 20mm 15mm 20mm 15mm; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center">

    <!-- Top Action Bar -->
    <div class="mb-6 flex items-center gap-3 no-print">
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-sans font-bold text-xs shadow-md transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/></svg>
            Cetak Berita Acara (Print / PDF)
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-sans font-semibold text-xs transition">
            Tutup
        </button>
    </div>

    <!-- Official Document Canvas (A4 Sheet) -->
    <div class="w-full max-w-4xl bg-white p-8 md:p-12 shadow-2xl border border-slate-300">
        
        <!-- Kop Surat Resmi -->
        <div class="text-center border-b-4 border-double border-black pb-4 mb-6 relative">
            <h3 class="text-base font-bold uppercase tracking-wide">PEMERINTAH KABUPATEN LAMPUNG TIMUR</h3>
            <h2 class="text-xl font-extrabold uppercase tracking-wider">DINAS PERPUSTAKAAN DAN KEARSIPAN</h2>
            <p class="text-xs italic mt-0.5">Komplek Perkantoran Pemerintah Daerah Kabupaten Lampung Timur, Sukadana</p>
            <p class="text-xs">Email: perpustakaan.lampungtimur@gmail.com &bull; Kode Pos 34194</p>
        </div>

        <!-- Judul Berita Acara -->
        <div class="text-center my-6">
            <h4 class="text-base font-bold underline uppercase tracking-wide">BERITA ACARA PELAYANAN PERPUSTAKAAN KELILING</h4>
            <p class="text-sm font-bold mt-1 font-mono">Nomor : {{ $beritaAcara->nomor_ba }}</p>
        </div>

        <!-- Narasi Pembuka -->
        <div class="text-sm leading-relaxed text-justify space-y-3">
            <p>
                Pada hari ini, <b>{{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->locale('id')->isoFormat('dddd') }}</b> 
                tanggal <b>{{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->locale('id')->isoFormat('D') }}</b> 
                bulan <b>{{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->locale('id')->isoFormat('MMMM') }}</b> 
                tahun <b>{{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->locale('id')->isoFormat('Y') }}</b>, 
                telah dilaksanakan kegiatan operasional Layanan Mobil Perpustakaan Keliling yang bertempat di:
            </p>

            <table class="w-full my-2 text-sm">
                <tr>
                    <td class="w-44 py-1 font-semibold">Nama Sekolah</td>
                    <td class="w-4 py-1">:</td>
                    <td class="py-1 font-bold">{{ $beritaAcara->sekolah->nama_sekolah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Alamat Sekolah</td>
                    <td class="py-1">:</td>
                    <td class="py-1">{{ $beritaAcara->sekolah->alamat ?? '-' }}, Kec. {{ $beritaAcara->sekolah->kecamatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Waktu Pelaksanaan</td>
                    <td class="py-1">:</td>
                    <td class="py-1">
                        Pukul {{ $beritaAcara->waktu_mulai ? substr($beritaAcara->waktu_mulai, 0, 5) : '08:30' }} s/d {{ $beritaAcara->waktu_selesai ? substr($beritaAcara->waktu_selesai, 0, 5) : '12:00' }} WIB
                    </td>
                </tr>
            </table>

            <p>Adapun kedua belah pihak yang melaksanakan dan menyaksikan kegiatan pelayanan ini adalah:</p>

            <!-- Detail Pihak -->
            <div class="space-y-2 pl-4">
                <div>
                    <p class="font-bold">1. Pihak Pertama (Petugas Layanan Dinas Perpustakaan dan Kearsipan):</p>
                    <table class="text-sm ml-4">
                        <tr><td class="w-28 py-0.5">Nama</td><td class="w-4">:</td><td class="font-bold">{{ $beritaAcara->pustakawan->nama ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">NIP</td><td>:</td><td>{{ $beritaAcara->pustakawan->nip ?? '-' }}</td></tr>
                        <tr><td class="py-0.5">Jabatan</td><td>:</td><td>{{ $beritaAcara->pustakawan->jabatan ?? 'Petugas Perpustakaan Keliling' }}</td></tr>
                    </table>
                </div>

                <div>
                    <p class="font-bold">2. Pihak Kedua (Perwakilan Pihak Sekolah):</p>
                    <table class="text-sm ml-4">
                        <tr><td class="w-28 py-0.5">Nama</td><td class="w-4">:</td><td class="font-bold">{{ $beritaAcara->nama_perwakilan_sekolah }}</td></tr>
                        <tr><td class="py-0.5">NIP</td><td>:</td><td>{{ $beritaAcara->nip_perwakilan_sekolah ?: '-' }}</td></tr>
                        <tr><td class="py-0.5">Asal Sekolah</td><td>:</td><td>{{ $beritaAcara->sekolah->nama_sekolah ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <p>
                Berdasarkan hasil rekapitulasi presensi digital, jumlah anggota siswa yang berkunjung dan memanfaatkan layanan membaca pada kegiatan ini adalah sebanyak 
                <b>{{ $beritaAcara->pengunjung->count() }} ({{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($beritaAcara->pengunjung->count())) }}) orang siswa</b> 
                dengan daftar nama terlampir.
            </p>

            @if($beritaAcara->catatan)
                <p><b>Catatan Pelaksanaan:</b> {{ $beritaAcara->catatan }}</p>
            @endif

            <p>
                Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap 2 (dua) untuk dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <!-- Signatures Block -->
        <div class="mt-12 pt-6 grid grid-cols-2 text-center text-sm">
            <div>
                <p>Pihak Kedua,<br>Perwakilan Sekolah</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $beritaAcara->nama_perwakilan_sekolah }}</p>
                <p class="text-xs">NIP. {{ $beritaAcara->nip_perwakilan_sekolah ?: '..........................................' }}</p>
            </div>

            <div>
                <p>Pihak Pertama,<br>Petugas Pustakawan</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $beritaAcara->pustakawan->nama ?? '-' }}</p>
                <p class="text-xs">NIP. {{ $beritaAcara->pustakawan->nip ?? '..........................................' }}</p>
            </div>
        </div>

        <!-- Mengetahui Kepala Dinas -->
        <div class="mt-10 text-center text-sm">
            <p>Mengetahui,<br>Kepala Dinas Perpustakaan dan Kearsipan<br>Kabupaten Lampung Timur</p>
            <div class="h-20"></div>
            <p class="font-bold underline">Drs. MULYANDA</p>
            <p class="text-xs">Pembina Utama Muda / NIP. 19650412 199203 1 004</p>
        </div>

        <!-- LAMPIRAN PRESENSI SISWA -->
        <div class="mt-16 pt-8 border-t-2 border-dashed border-slate-300">
            <div class="text-center mb-4">
                <h5 class="text-sm font-bold uppercase underline">LAMPIRAN DAFTAR PRESENSI PENGUNJUNG</h5>
                <p class="text-xs">Nomor Berita Acara: {{ $beritaAcara->nomor_ba }} &bull; Tanggal: {{ \Carbon\Carbon::parse($beritaAcara->tgl_kunjungan)->format('d/m/Y') }}</p>
            </div>

            <table class="w-full text-xs border-collapse border border-black">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border border-black py-2 px-2 w-10 text-center">No</th>
                        <th class="border border-black py-2 px-3 text-center">No. Anggota</th>
                        <th class="border border-black py-2 px-3 text-left">Nama Lengkap Siswa</th>
                        <th class="border border-black py-2 px-2 text-center">L/P</th>
                        <th class="border border-black py-2 px-3 text-center">Kelas</th>
                        <th class="border border-black py-2 px-4 text-center">Tanda Tangan / Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritaAcara->pengunjung as $idx => $mhs)
                        <tr>
                            <td class="border border-black py-1.5 px-2 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-black py-1.5 px-3 text-center font-mono font-bold">{{ $mhs->nomor_anggota }}</td>
                            <td class="border border-black py-1.5 px-3 font-semibold">{{ $mhs->nama }}</td>
                            <td class="border border-black py-1.5 px-2 text-center">{{ $mhs->jenis_kelamin }}</td>
                            <td class="border border-black py-1.5 px-3 text-center">{{ $mhs->kelas }}</td>
                            <td class="border border-black py-1.5 px-4 text-center text-slate-400">
                                {{ $idx + 1 }}. .......................
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-black py-4 text-center italic">Tidak ada siswa terdaftar pada kunjungan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>

