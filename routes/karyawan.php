<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Controllers\Karyawan\AbsensiController;
use App\Http\Controllers\Karyawan\RekapController;
use App\Http\Controllers\Karyawan\CutiIzinController;
use App\Http\Controllers\Karyawan\PengumumanController;
use App\Http\Controllers\Karyawan\ProfilController;

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