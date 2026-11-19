<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id('id_ba');
            $table->string('nomor_ba', 50)->unique();
            $table->unsignedBigInteger('id_sekolah');
            $table->unsignedBigInteger('id_pustakawan');
            $table->date('tgl_kunjungan')->index();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('nama_perwakilan_sekolah', 100);
            $table->string('nip_perwakilan_sekolah', 30)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_sekolah')
                  ->references('id_sekolah')
                  ->on('sekolah')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('id_pustakawan')
                  ->references('id_pustakawan')
                  ->on('pustakawan')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};

