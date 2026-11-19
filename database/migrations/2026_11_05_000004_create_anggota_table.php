<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->unsignedBigInteger('id_sekolah')->index();
            $table->string('nomor_anggota', 30)->unique();
            $table->string('nisn', 20)->nullable();
            $table->string('nama', 100)->index();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kelas', 20);
            $table->text('alamat')->nullable();
            $table->enum('status_anggota', ['aktif', 'lulus', 'pindah'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_sekolah')
                  ->references('id_sekolah')
                  ->on('sekolah')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};

