<div class="overflow-x-auto">
    <table class="w-full text-left text-xs border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                <th class="py-3.5 px-4 w-12 text-center">No</th>
                <th class="py-3.5 px-4">Nama Lengkap</th>
                <th class="py-3.5 px-4">NIP</th>
                <th class="py-3.5 px-4">Jabatan</th>
                <th class="py-3.5 px-4">No. Kontak</th>
                <th class="py-3.5 px-4 text-center">Tugas Kunjungan</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($pustakawan as $index => $item)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                        {{ $pustakawan->firstItem() + $index }}
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs mr-2.5 border border-teal-200">
                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                            </div>
                            <span class="font-bold text-slate-900 text-sm">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                        {{ $item->nip }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $item->jabatan }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-slate-600">
                        {{ $item->no_telp ?: '-' }}
                    </td>
                    <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                        {{ $item->berita_acara_count }}x bertugas
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('pustakawan.edit', $item->id_pustakawan) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </a>
                        <form action="{{ route('pustakawan.destroy', $item->id_pustakawan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas {{ $item->nama }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition">
                                <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-slate-400">
                        <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                        <p>Tidak ada data pustakawan ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pustakawan->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $pustakawan->links() }}
    </div>
@endif

