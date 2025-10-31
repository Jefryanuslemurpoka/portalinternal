<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Controllers\Karyawan\AbsensiController;
use App\Http\Controllers\Karyawan\RekapController;
use App\Http\Controllers\Karyawan\CutiIzinController;
use App\Http\Controllers\Karyawan\PengumumanController;
use App\Http\Controllers\Karyawan\ProfilController;
use App\Http\Controllers\Karyawan\SuratController;
use App\Http\Controllers\Karyawan\ServerLogController;

// Karyawan Routes - dengan middleware karyawan
Route::prefix('karyawan')->middleware(['auth', 'karyawan'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('karyawan.dashboard');

    // Absensi Check-in/Check-out
    Route::prefix('absensi')->name('karyawan.absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::post('/check-in', [AbsensiController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [AbsensiController::class, 'checkOut'])->name('checkout');
        Route::get('/today', [AbsensiController::class, 'today'])->name('today');
    });

    // Rekap Absensi
    Route::prefix('rekap')->name('karyawan.rekap.')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('index');
        Route::get('/harian', [RekapController::class, 'harian'])->name('harian');
        Route::get('/mingguan', [RekapController::class, 'mingguan'])->name('mingguan');
        Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
    });

    // Pengajuan Cuti/Izin
    Route::prefix('cuti-izin')->name('karyawan.cutiizin.')->group(function () {
        Route::get('/', [CutiIzinController::class, 'index'])->name('index');
        Route::get('/create', [CutiIzinController::class, 'create'])->name('create');
        Route::post('/', [CutiIzinController::class, 'store'])->name('store');
        Route::get('/{id}', [CutiIzinController::class, 'show'])->name('show');
        Route::delete('/{id}', [CutiIzinController::class, 'destroy'])->name('destroy');
    });

    // Surat/Dokumen - LENGKAP dengan semua route yang dibutuhkan
    Route::prefix('surat')->name('karyawan.surat.')->group(function () {
        Route::get('/', [SuratController::class, 'index'])->name('index');
        Route::get('/create', [SuratController::class, 'create'])->name('create');
        Route::post('/', [SuratController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SuratController::class, 'edit'])->name('edit'); // TAMBAHKAN INI
        Route::put('/{id}', [SuratController::class, 'update'])->name('update'); // TAMBAHKAN INI
        Route::get('/{id}/download', [SuratController::class, 'download'])->name('download'); // TAMBAHKAN INI
        Route::delete('/{id}', [SuratController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [SuratController::class, 'show'])->name('show'); // Pindahkan ke paling bawah
    });

    // Server Log
    Route::prefix('serverlog')->name('karyawan.serverlog.')->group(function () {
        Route::get('/', [ServerLogController::class, 'index'])->name('index');
        Route::get('/{id}', [ServerLogController::class, 'show'])->name('show');
    });

    // Pengumuman
    Route::prefix('pengumuman')->name('karyawan.pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/{id}', [PengumumanController::class, 'show'])->name('show');
    });

    // Profil
    Route::prefix('profil')->name('karyawan.profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
        Route::post('/foto', [ProfilController::class, 'updateFoto'])->name('foto');
    });

});