<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id('id_sekolah');
            $table->string('npsn', 20)->nullable()->unique();
            $table->string('nama_sekolah', 100)->index();
            $table->string('nama_kepsek', 100);
            $table->text('alamat');
            $table->string('kecamatan', 50)->default('Lampung Timur');
            $table->string('kontak_sekolah', 25)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};

