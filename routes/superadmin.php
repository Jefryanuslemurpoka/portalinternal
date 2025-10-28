<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\KaryawanController;
use App\Http\Controllers\SuperAdmin\AbsensiController;
use App\Http\Controllers\SuperAdmin\CutiIzinController;
use App\Http\Controllers\SuperAdmin\SuratController;
use App\Http\Controllers\SuperAdmin\ServerLogController;
use App\Http\Controllers\SuperAdmin\PengumumanController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\PengaturanController;

// Super Admin Routes - dengan middleware superadmin
Route::prefix('superadmin')->middleware(['auth', 'superadmin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');

    // Manajemen Karyawan
    Route::prefix('karyawan')->name('superadmin.karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('/{uuid}', [KaryawanController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [KaryawanController::class, 'destroy'])->name('destroy');
        Route::post('/{uuid}/reset-password', [KaryawanController::class, 'resetPassword'])->name('reset-password');
    });

    // Manajemen Absensi
    Route::prefix('absensi')->name('superadmin.absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::get('/create', [AbsensiController::class, 'create'])->name('create');
        Route::post('/', [AbsensiController::class, 'store'])->name('store');
        Route::post('/export', [AbsensiController::class, 'export'])->name('export');
        Route::get('/filter', [AbsensiController::class, 'filter'])->name('filter');
        Route::get('/{id}/edit', [AbsensiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AbsensiController::class, 'update'])->name('update');
        Route::get('/{id}', [AbsensiController::class, 'show'])->name('show');
        Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('destroy');
    });

    // Persetujuan Cuti/Izin
    Route::prefix('cuti-izin')->name('superadmin.cutiizin.')->group(function () {
        Route::get('/', [CutiIzinController::class, 'index'])->name('index');
        Route::get('/create', [CutiIzinController::class, 'create'])->name('create');
        Route::post('/', [CutiIzinController::class, 'store'])->name('store');
        Route::get('/{id}', [CutiIzinController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CutiIzinController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CutiIzinController::class, 'update'])->name('update');
        Route::delete('/{id}', [CutiIzinController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [CutiIzinController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CutiIzinController::class, 'reject'])->name('reject');
    });

    // Log Book Surat
    Route::prefix('surat')->name('superadmin.surat.')->group(function () {
        Route::get('/', [SuratController::class, 'index'])->name('index');
        Route::get('/create', [SuratController::class, 'create'])->name('create');
        Route::post('/', [SuratController::class, 'store'])->name('store');
        Route::get('/{id}', [SuratController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SuratController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SuratController::class, 'update'])->name('update');
        Route::delete('/{id}', [SuratController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [SuratController::class, 'download'])->name('download');
    });

    // Log Book Server
    Route::prefix('server-log')->name('superadmin.serverlog.')->group(function () {
        Route::get('/', [ServerLogController::class, 'index'])->name('index');
        Route::get('/create', [ServerLogController::class, 'create'])->name('create');
        Route::post('/', [ServerLogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ServerLogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ServerLogController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServerLogController::class, 'destroy'])->name('destroy');
    });

    // Pengumuman
    Route::prefix('pengumuman')->name('superadmin.pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengumumanController::class, 'destroy'])->name('destroy');
    });

    // Laporan
    Route::prefix('laporan')->name('superadmin.laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/absensi', [LaporanController::class, 'absensi'])->name('absensi');
        Route::get('/cuti-izin', [LaporanController::class, 'cutiIzin'])->name('cutiizin');
        Route::get('/karyawan', [LaporanController::class, 'karyawan'])->name('karyawan');
        Route::post('/export', [LaporanController::class, 'export'])->name('export');
    });

    // Pengaturan Sistem
    Route::prefix('pengaturan')->name('superadmin.pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::put('/jam-kerja', [PengaturanController::class, 'updateJamKerja'])->name('jam-kerja');
        Route::put('/lokasi', [PengaturanController::class, 'updateLokasi'])->name('lokasi');
        Route::put('/general', [PengaturanController::class, 'updateGeneral'])->name('general');
    });

});