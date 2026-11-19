<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\BeritaAcara;
use App\Models\DetailKunjungan;
use App\Models\Pustakawan;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Sekolah (Sampel Skripsi: SDN 1 Gunung Tiga)
        $sekolah = Sekolah::create([
            'id_sekolah'     => 1,
            'npsn'           => '10801234',
            'nama_sekolah'   => 'SDN 1 Gunung Tiga',
            'nama_kepsek'    => 'Drs. H. Mulyadi',
            'alamat'         => 'Desa Gunung Tiga, Kec. Batanghari Nuban, Kab. Lampung Timur',
            'kecamatan'      => 'Batanghari Nuban',
            'kontak_sekolah' => '081369001122',
        ]);

        // 2. Data Petugas Pustakawan
        $pustakawan = Pustakawan::create([
            'id_pustakawan' => 1,
            'nip'           => '198501012010011005',
            'nama'          => 'Dedi Irawan, S.Kom., M.T.I',
            'jabatan'       => 'Koordinator Pustakawan Keliling',
            'no_telp'       => '081234567890',
        ]);

        // 3. Data User Login (Admin & Petugas)
        // Akun 1: Administrator (Zahra Nurhaliza)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'id_user'       => 1,
                'id_pustakawan' => null,
                'nama_lengkap'  => 'Zahra Nurhaliza',
                'password'      => Hash::make('admin123'),
                'role'          => 'admin',
                'status_aktif'  => true,
            ]
        );

        // Akun 2: Petugas Pustakawan Layanan Keliling (Dedi Irawan)
        User::updateOrCreate(
            ['username' => 'dedi'],
            [
                'id_user'       => 2,
                'id_pustakawan' => $pustakawan->id_pustakawan,
                'nama_lengkap'  => 'Dedi Irawan, S.Kom., M.T.I',
                'password'      => Hash::make('petugas123'),
                'role'          => 'pustakawan',
                'status_aktif'  => true,
            ]
        );

        // 4. Data Anggota Siswa
        $anggota1 = Anggota::create([
            'id_anggota'     => 1,
            'id_sekolah'     => $sekolah->id_sekolah,
            'nomor_anggota'  => 'AGT-2026-0001',
            'nisn'           => '0081234561',
            'nama'           => 'Ahmad Fauzi',
            'jenis_kelamin'  => 'L',
            'kelas'          => 'IV A',
            'alamat'         => 'Dusun 1, Gunung Tiga',
            'status_anggota' => 'aktif',
        ]);

        $anggota2 = Anggota::create([
            'id_anggota'     => 2,
            'id_sekolah'     => $sekolah->id_sekolah,
            'nomor_anggota'  => 'AGT-2026-0002',
            'nisn'           => '0081234562',
            'nama'           => 'Siti Rahmawati',
            'jenis_kelamin'  => 'P',
            'kelas'          => 'IV A',
            'alamat'         => 'Dusun 2, Gunung Tiga',
            'status_anggota' => 'aktif',
        ]);

        $anggota3 = Anggota::create([
            'id_anggota'     => 3,
            'id_sekolah'     => $sekolah->id_sekolah,
            'nomor_anggota'  => 'AGT-2026-0003',
            'nisn'           => '0081234563',
            'nama'           => 'Budi Santoso',
            'jenis_kelamin'  => 'L',
            'kelas'          => 'V B',
            'alamat'         => 'Dusun 1, Gunung Tiga',
            'status_anggota' => 'aktif',
        ]);

        // 5. Data Berita Acara Kunjungan
        $ba = BeritaAcara::create([
            'id_ba'                   => 1,
            'nomor_ba'                => 'BA/2026/XI/001',
            'id_sekolah'              => $sekolah->id_sekolah,
            'id_pustakawan'           => $pustakawan->id_pustakawan,
            'tgl_kunjungan'           => '2026-11-05',
            'waktu_mulai'             => '08:30:00',
            'waktu_selesai'           => '12:00:00',
            'nama_perwakilan_sekolah' => 'Siti Maryam, S.Pd',
            'nip_perwakilan_sekolah'  => '198902022015022001',
            'catatan'                 => 'Pelayanan membaca di perpustakaan keliling berjalan tertib dan antusias.',
        ]);

        // 6. Data Detail Presensi Siswa
        DetailKunjungan::create([
            'id_ba'      => $ba->id_ba,
            'id_anggota' => $anggota1->id_anggota,
        ]);

        DetailKunjungan::create([
            'id_ba'      => $ba->id_ba,
            'id_anggota' => $anggota2->id_anggota,
        ]);

        DetailKunjungan::create([
            'id_ba'      => $ba->id_ba,
            'id_anggota' => $anggota3->id_anggota,
        ]);
    }
}
