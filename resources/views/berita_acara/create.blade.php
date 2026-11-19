@extends('layouts.app')

@section('title', 'Buat Berita Acara Baru')
@section('page_title', 'Buat Berita Acara & Presensi')
@section('page_subtitle', 'Dokumentasi resmi serah terima kegiatan layanan perpustakaan keliling di sekolah')

@section('content')
<div class="max-w-4xl mx-auto" x-data="beritaAcaraForm()">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8">
        
        <div class="flex items-center justify-between pb-5 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Formulir Berita Acara Kunjungan</h3>
                <p class="text-xs text-slate-500">Mencatat kesepakatan dua pihak dan presensi siswa yang membaca</p>
            </div>
            <a href="{{ route('berita-acara.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <p class="font-bold mb-1">Periksa kembali isian form:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('berita-acara.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Berita Acara -->
            <div>
                <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-3 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center mr-2 text-indigo-600">1</span>
                    Identitas Berita Acara & Waktu Pelaksanaan
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Berita Acara <span class="text-rose-500">*</span></label>
                        <input type="text" name="nomor_ba" value="{{ old('nomor_ba', $autoNomorBa) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tgl_kunjungan" value="{{ old('tgl_kunjungan', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Mulai Layanan</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', '08:30') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Selesai Layanan</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', '12:00') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Pihak Terkait (Pustakawan & Perwakilan Sekolah) -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-3 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center mr-2 text-indigo-600">2</span>
                    Lokasi & Penandatangan Berita Acara (2 Pihak)
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sekolah Tujuan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Sekolah Tujuan (Lokasi) <span class="text-rose-500">*</span></label>
                        <select name="id_sekolah" x-model="selectedSekolahId" @change="fetchAnggota()" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">-- Pilih Sekolah Tujuan --</option>
                            @foreach($sekolahList as $sek)
                                <option value="{{ $sek->id_sekolah }}" {{ old('id_sekolah', $selectedSekolahId) == $sek->id_sekolah ? 'selected' : '' }}>
                                    {{ $sek->nama_sekolah }} (Kec. {{ $sek->kecamatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Petugas Pustakawan (Pihak 1) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Petugas Pustakawan (Pihak I) <span class="text-rose-500">*</span></label>
                        <select name="id_pustakawan" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">-- Pilih Petugas Pustakawan --</option>
                            @foreach($pustakawanList as $pus)
                                <option value="{{ $pus->id_pustakawan }}" {{ old('id_pustakawan', Auth::user()->id_pustakawan) == $pus->id_pustakawan ? 'selected' : '' }}>
                                    {{ $pus->nama }} (NIP: {{ $pus->nip }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <!-- Perwakilan Sekolah (Pihak 2) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Perwakilan Sekolah (Pihak II) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_perwakilan_sekolah" value="{{ old('nama_perwakilan_sekolah') }}" required placeholder="Nama Guru Pendamping / Kepala Sekolah"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP Perwakilan Sekolah</label>
                        <input type="text" name="nip_perwakilan_sekolah" value="{{ old('nip_perwakilan_sekolah') }}" placeholder="Nomor Induk Pegawai Guru (Opsional)"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Resume Kegiatan</label>
                    <textarea name="catatan" rows="2" placeholder="Tuliskan catatan kelancaran kegiatan, buku yang paling diminati siswa, dll..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('catatan', 'Pelayanan membaca di perpustakaan keliling berjalan tertib dan antusias.') }}</textarea>
                </div>
            </div>

            <!-- Section 3: Presensi Siswa yang Hadir (Kaidah 3NF) -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider flex items-center">
                        <span class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center mr-2 text-indigo-600">3</span>
                        Presensi Siswa yang Hadir Membaca (Otomatis Menghitung Pengunjung)
                    </h4>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200" x-text="selectedCount + ' siswa dipilih'"></span>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200 text-xs">
                        <label class="inline-flex items-center cursor-pointer font-bold text-slate-700">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                            <span>Centang Semua Siswa</span>
                        </label>
                        <span class="text-[11px] text-slate-500">Centang siswa yang berkunjung ke mobil hari ini</span>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="py-6 text-center text-xs text-slate-400">
                        <i class="fa-solid fa-spinner fa-spin text-indigo-600 text-lg mb-1 block"></i>
                        <span>Memuat data siswa sekolah...</span>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loading && anggotaList.length === 0" class="py-6 text-center text-xs text-slate-400">
                        <i class="fa-regular fa-face-frown text-2xl mb-1 block"></i>
                        <span>Belum ada siswa terdaftar di sekolah ini. Tambahkan anggota siswa terlebih dahulu.</span>
                    </div>

                    <!-- Checklist Siswa Grid -->
                    <div x-show="!loading && anggotaList.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto pr-1">
                        <template x-for="item in anggotaList" :key="item.id_anggota">
                            <label class="flex items-center p-2.5 rounded-xl bg-white border border-slate-200/80 hover:border-indigo-400 cursor-pointer transition select-none">
                                <input type="checkbox" name="anggota_hadir[]" :value="item.id_anggota" x-model="selectedAnggota"
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-900 truncate" x-text="item.nama"></p>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                        <span class="font-mono text-indigo-600 font-semibold" x-text="item.nomor_anggota"></span>
                                        <span>&bull;</span>
                                        <span x-text="'Kelas: ' + item.kelas"></span>
                                        <span>&bull;</span>
                                        <span x-text="item.jenis_kelamin == 'L' ? 'L' : 'P'" class="font-bold"></span>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('berita-acara.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center">
                    <i class="fa-solid fa-file-circle-check mr-2"></i> Simpan Berita Acara & Presensi
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
function beritaAcaraForm() {
    return {
        selectedSekolahId: '{{ old("id_sekolah", $selectedSekolahId) }}',
        anggotaList: @json($anggotaList),
        selectedAnggota: @json(old('anggota_hadir', $anggotaList->pluck('id_anggota')->toArray())),
        loading: false,

        get selectedCount() {
            return this.selectedAnggota.length;
        },

        async fetchAnggota() {
            if (!this.selectedSekolahId) {
                this.anggotaList = [];
                this.selectedAnggota = [];
                return;
            }
            this.loading = true;
            try {
                const response = await fetch(`/api/sekolah/${this.selectedSekolahId}/anggota`);
                const data = await response.json();
                this.anggotaList = data;
                // Auto check all students by default
                this.selectedAnggota = data.map(i => i.id_anggota);
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        toggleAll(e) {
            if (e.target.checked) {
                this.selectedAnggota = this.anggotaList.map(i => i.id_anggota);
            } else {
                this.selectedAnggota = [];
            }
        }
    }
}
</script>
@endpush

