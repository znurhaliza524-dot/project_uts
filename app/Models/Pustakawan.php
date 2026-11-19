<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pustakawan extends Model
{
    use HasFactory;

    protected $table = 'pustakawan';
    protected $primaryKey = 'id_pustakawan';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'no_telp',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id_pustakawan', 'id_pustakawan');
    }

    public function beritaAcara()
    {
        return $this->hasMany(BeritaAcara::class, 'id_pustakawan', 'id_pustakawan');
    }
}

