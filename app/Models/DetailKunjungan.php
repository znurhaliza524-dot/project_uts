<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKunjungan extends Model
{
    use HasFactory;

    protected $table = 'detail_kunjungan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false; // Memiliki kolom custom waktu_hadir

    protected $fillable = [
        'id_ba',
        'id_anggota',
        'waktu_hadir',
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'id_ba', 'id_ba');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }
}

