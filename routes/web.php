<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PustakawanController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Harus Login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil & Keamanan Akun
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Master Sekolah
    Route::resource('sekolah', SekolahController::class);

    // Master Pustakawan
    Route::resource('pustakawan', PustakawanController::class);

    // Master Anggota (Batch Print & CSV Export)
    Route::get('/anggota/kartu-massal', [AnggotaController::class, 'cetakKartuMassal'])->name('anggota.kartu.massal');
    Route::get('/anggota/export-csv', [AnggotaController::class, 'exportCsv'])->name('anggota.export.csv');
    Route::resource('anggota', AnggotaController::class)->parameters([
        'anggota' => 'anggota'
    ]);
    Route::get('/anggota/{anggota}/kartu', [AnggotaController::class, 'cetakKartu'])->name('anggota.kartu');

    // Transaksi Berita Acara & Presensi Kunjungan
    Route::resource('berita-acara', BeritaAcaraController::class)->parameters([
        'berita-acara' => 'beritaAcara'
    ]);
    Route::get('/berita-acara/{beritaAcara}/cetak', [BeritaAcaraController::class, 'cetak'])->name('berita-acara.cetak');
    Route::get('/api/sekolah/{id}/anggota', [BeritaAcaraController::class, 'getAnggotaBySekolah'])->name('api.sekolah.anggota');

    // Laporan Tahunan & Rekapitulasi
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/anggota', [LaporanController::class, 'anggota'])->name('anggota');
        Route::get('/anggota/cetak', [LaporanController::class, 'cetakAnggota'])->name('anggota.cetak');
        Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/kunjungan/cetak', [LaporanController::class, 'cetakKunjungan'])->name('kunjungan.cetak');
        Route::get('/kunjungan/export-csv', [LaporanController::class, 'exportCsvKunjungan'])->name('kunjungan.csv');
    });
});
