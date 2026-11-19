<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $table = 'berita_acara';
    protected $primaryKey = 'id_ba';

    protected $fillable = [
        'nomor_ba',
        'id_sekolah',
        'id_pustakawan',
        'tgl_kunjungan',
        'waktu_mulai',
        'waktu_selesai',
        'nama_perwakilan_sekolah',
        'nip_perwakilan_sekolah',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kunjungan' => 'date',
        ];
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function pustakawan()
    {
        return $this->belongsTo(Pustakawan::class, 'id_pustakawan', 'id_pustakawan');
    }

    public function detailKunjungan()
    {
        return $this->hasMany(DetailKunjungan::class, 'id_ba', 'id_ba');
    }

    public function pengunjung()
    {
        return $this->belongsToMany(Anggota::class, 'detail_kunjungan', 'id_ba', 'id_anggota');
    }

    // Dynamic 3NF attribute: Menghitung jumlah pengunjung secara realtime
    public function getJumlahPengunjungAttribute(): int
    {
        return $this->detailKunjungan()->count();
    }
}

