<div class="overflow-x-auto">
    <table class="w-full text-left text-xs border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                <th class="py-3.5 px-4 w-12 text-center">No</th>
                <th class="py-3.5 px-4">No. Anggota</th>
                <th class="py-3.5 px-4">Nama Siswa</th>
                <th class="py-3.5 px-4">Gender</th>
                <th class="py-3.5 px-4">Sekolah & Kelas</th>
                <th class="py-3.5 px-4">Status</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($anggota as $index => $item)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                        {{ $anggota->firstItem() + $index }}
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200/60 inline-block">
                            {{ $item->nomor_anggota }}
                        </span>
                        @if($item->nisn)
                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">NISN: {{ $item->nisn }}</p>
                        @endif
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-bold text-slate-900 text-sm block">{{ $item->nama }}</span>
                        <span class="text-[11px] text-slate-400 truncate max-w-xs">{{ $item->alamat ?: 'Alamat belum diisi' }}</span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($item->jenis_kelamin == 'L')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                <i class="fa-solid fa-mars mr-1"></i> Laki-laki
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-pink-50 text-pink-700 border border-pink-200">
                                <i class="fa-solid fa-venus mr-1"></i> Perempuan
                            </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="font-semibold text-slate-900 block">{{ $item->sekolah->nama_sekolah ?? '-' }}</span>
                        <span class="text-[11px] font-bold text-slate-500">Kelas: {{ $item->kelas }}</span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($item->status_anggota == 'aktif')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @elseif($item->status_anggota == 'lulus')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">Lulus</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700">Pindah</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                        <a href="{{ route('anggota.kartu', $item->id_anggota) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs transition" title="Cetak Kartu Anggota">
                            <i class="fa-solid fa-id-card-clip mr-1"></i> Kartu
                        </a>
                        <a href="{{ route('anggota.edit', $item->id_anggota) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </a>
                        <form action="{{ route('anggota.destroy', $item->id_anggota) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota {{ $item->nama }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition" title="Hapus">
                                <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-slate-400">
                        <i class="fa-solid fa-users-slash text-3xl mb-2"></i>
                        <p>Tidak ada data siswa anggota ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($anggota->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $anggota->links() }}
    </div>
@endif

