<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'nama_kepsek',
        'alamat',
        'kecamatan',
        'kontak_sekolah',
    ];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_sekolah', 'id_sekolah');
    }

    public function beritaAcara()
    {
        return $this->hasMany(BeritaAcara::class, 'id_sekolah', 'id_sekolah');
    }
}

