<?php

namespace Database\Seeders;

use App\Models\Pustakawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan pustakawan Dedi Irawan ada
        $pustakawan = Pustakawan::firstOrCreate(
            ['id_pustakawan' => 1],
            [
                'nip'     => '198501012010011005',
                'nama'    => 'Dedi Irawan, S.Kom., M.T.I',
                'jabatan' => 'Koordinator Pustakawan Keliling',
                'no_telp' => '081234567890',
            ]
        );

        $pustakawan->update([
            'nama'    => 'Dedi Irawan, S.Kom., M.T.I',
            'jabatan' => 'Koordinator Pustakawan Keliling',
        ]);

        // 1. Admin: Zahra Nurhaliza
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'id_pustakawan' => null,
                'nama_lengkap'  => 'Zahra Nurhaliza',
                'password'      => Hash::make('admin123'),
                'role'          => 'admin',
                'status_aktif'  => true,
            ]
        );

        // 2. Petugas: Dedi Irawan
        User::updateOrCreate(
            ['username' => 'dedi'],
            [
                'id_pustakawan' => $pustakawan->id_pustakawan,
                'nama_lengkap'  => 'Dedi Irawan, S.Kom., M.T.I',
                'password'      => Hash::make('petugas123'),
                'role'          => 'pustakawan',
                'status_aktif'  => true,
            ]
        );
    }
}

