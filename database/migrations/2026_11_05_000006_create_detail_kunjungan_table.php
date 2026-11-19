<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_kunjungan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_ba');
            $table->unsignedBigInteger('id_anggota');
            $table->timestamp('waktu_hadir')->useCurrent();

            $table->foreign('id_ba')
                  ->references('id_ba')
                  ->on('berita_acara')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_anggota')
                  ->references('id_anggota')
                  ->on('anggota')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            // Mencegah duplikasi data kehadiran siswa di kunjungan yang sama
            $table->unique(['id_ba', 'id_anggota'], 'uq_kunjungan_anggota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_kunjungan');
    }
};

