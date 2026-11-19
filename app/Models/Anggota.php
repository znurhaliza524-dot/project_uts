<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_sekolah',
        'nomor_anggota',
        'nisn',
        'nama',
        'jenis_kelamin',
        'kelas',
        'alamat',
        'status_anggota',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function detailKunjungan()
    {
        return $this->hasMany(DetailKunjungan::class, 'id_anggota', 'id_anggota');
    }

    public function kunjungan()
    {
        return $this->belongsToMany(BeritaAcara::class, 'detail_kunjungan', 'id_anggota', 'id_ba');
    }
}

