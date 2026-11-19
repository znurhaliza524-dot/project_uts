<div class="overflow-x-auto">
    <table class="w-full text-left text-xs border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                <th class="py-3.5 px-4 w-12 text-center">No</th>
                <th class="py-3.5 px-4">Nama Sekolah</th>
                <th class="py-3.5 px-4">NPSN</th>
                <th class="py-3.5 px-4">Kepala Sekolah</th>
                <th class="py-3.5 px-4">Kecamatan & Alamat</th>
                <th class="py-3.5 px-4 text-center">Total Siswa</th>
                <th class="py-3.5 px-4 text-center">Kunjungan</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($sekolah as $index => $item)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                        {{ $sekolah->firstItem() + $index }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-bold text-slate-900 text-sm block">{{ $item->nama_sekolah }}</span>
                        <span class="text-[11px] text-slate-400"><i class="fa-solid fa-phone mr-1"></i>{{ $item->kontak_sekolah ?: '-' }}</span>
                    </td>
                    <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                        {{ $item->npsn ?: '-' }}
                    </td>
                    <td class="py-3.5 px-4 font-medium text-slate-700">
                        {{ $item->nama_kepsek }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-semibold text-slate-800">Kec. {{ $item->kecamatan }}</span>
                        <p class="text-[11px] text-slate-400 truncate max-w-xs">{{ $item->alamat }}</p>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <a href="{{ route('anggota.index', ['id_sekolah' => $item->id_sekolah]) }}" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                            {{ $item->anggota_count }} siswa
                        </a>
                    </td>
                    <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                        {{ $item->berita_acara_count }}x
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                        @if(Auth::user()?->isAdmin())
                            <a href="{{ route('sekolah.edit', $item->id_sekolah) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs transition" title="Edit">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <form action="{{ route('sekolah.destroy', $item->id_sekolah) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sekolah {{ $item->nama_sekolah }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[11px] font-medium border border-slate-200">
                                <i class="fa-solid fa-eye mr-1 text-slate-400"></i> Hanya Lihat
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-slate-400">
                        <i class="fa-solid fa-school-circle-xmark text-3xl mb-2"></i>
                        <p>Tidak ada data sekolah ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($sekolah->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $sekolah->links() }}
    </div>
@endif

