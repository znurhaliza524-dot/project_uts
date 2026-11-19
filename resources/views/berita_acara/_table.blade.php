<div class="overflow-x-auto">
    <table class="w-full text-left text-xs border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                <th class="py-3.5 px-4 w-12 text-center">No</th>
                <th class="py-3.5 px-4">Nomor Berita Acara</th>
                <th class="py-3.5 px-4">Tanggal & Waktu</th>
                <th class="py-3.5 px-4">Sekolah Tujuan</th>
                <th class="py-3.5 px-4">Petugas Pustakawan</th>
                <th class="py-3.5 px-4">Perwakilan Sekolah</th>
                <th class="py-3.5 px-4 text-center">Jml Pengunjung</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($beritaAcara as $index => $item)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                        {{ $beritaAcara->firstItem() + $index }}
                    </td>
                    <td class="py-3.5 px-4">
                        <a href="{{ route('berita-acara.show', $item->id_ba) }}" class="font-mono font-bold text-indigo-700 hover:underline block text-sm">
                            {{ $item->nomor_ba }}
                        </a>
                        @if($item->catatan)
                            <p class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">{{ $item->catatan }}</p>
                        @endif
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-bold text-slate-900 block">{{ \Carbon\Carbon::parse($item->tgl_kunjungan)->locale('id')->isoFormat('D MMMM Y') }}</span>
                        <span class="text-[11px] text-slate-400 font-mono">
                            {{ $item->waktu_mulai ? substr($item->waktu_mulai, 0, 5) : '-' }} s/d {{ $item->waktu_selesai ? substr($item->waktu_selesai, 0, 5) : '-' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-900">
                        {{ $item->sekolah->nama_sekolah ?? '-' }}
                        <p class="text-[11px] text-slate-400 font-normal">Kec. {{ $item->sekolah->kecamatan ?? '-' }}</p>
                    </td>
                    <td class="py-3.5 px-4 text-slate-700 font-medium">
                        {{ $item->pustakawan->nama ?? '-' }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-semibold text-slate-800 block">{{ $item->nama_perwakilan_sekolah }}</span>
                        @if($item->nip_perwakilan_sekolah)
                            <span class="text-[10px] text-slate-400 font-mono">NIP: {{ $item->nip_perwakilan_sekolah }}</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fa-solid fa-user-check mr-1 text-[10px]"></i> {{ $item->detail_kunjungan_count }} siswa
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('berita-acara.show', $item->id_ba) }}" class="inline-flex items-center px-2 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition" title="Lihat Presensi">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('berita-acara.cetak', $item->id_ba) }}" target="_blank" class="inline-flex items-center px-2 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs transition" title="Cetak Berita Acara">
                            <i class="fa-solid fa-print"></i>
                        </a>
                        <a href="{{ route('berita-acara.edit', $item->id_ba) }}" class="inline-flex items-center px-2 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('berita-acara.destroy', $item->id_ba) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Berita Acara {{ $item->nomor_ba }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-slate-400">
                        <i class="fa-solid fa-file-excel text-3xl mb-2"></i>
                        <p>Tidak ada berita acara kunjungan ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($beritaAcara->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $beritaAcara->links() }}
    </div>
@endif

