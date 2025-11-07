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

// ========================================
// SUPER ADMIN ROUTES
// Middleware: auth + superadmin
// ========================================
Route::prefix('superadmin')->middleware(['auth', 'superadmin'])->group(function () {
    
    // ========================================
    // DASHBOARD
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');

    // ========================================
    // MANAJEMEN KARYAWAN
    // ========================================
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

    // ========================================
    // MANAJEMEN ABSENSI
    // ========================================
    Route::prefix('absensi')->name('superadmin.absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::get('/create', [AbsensiController::class, 'create'])->name('create');
        Route::post('/', [AbsensiController::class, 'store'])->name('store');
        Route::get('/filter', [AbsensiController::class, 'filter'])->name('filter');
        Route::post('/export', [AbsensiController::class, 'export'])->name('export');
        Route::get('/{id}', [AbsensiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AbsensiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AbsensiController::class, 'update'])->name('update');
        Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PERSETUJUAN CUTI/IZIN
    // ========================================
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

    // ========================================
    // LOG BOOK SURAT
    // ========================================
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

    // ========================================
    // LOG BOOK SERVER
    // ========================================
    Route::prefix('server-log')->name('superadmin.serverlog.')->group(function () {
        Route::get('/', [ServerLogController::class, 'index'])->name('index');
        Route::get('/create', [ServerLogController::class, 'create'])->name('create');
        Route::post('/', [ServerLogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ServerLogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ServerLogController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServerLogController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PENGUMUMAN
    // ========================================
    Route::prefix('pengumuman')->name('superadmin.pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengumumanController::class, 'destroy'])->name('destroy');
        
        // Test WhatsApp Connection
        Route::post('/test-whatsapp', [PengumumanController::class, 'testWhatsApp'])->name('test-whatsapp');
    });

    // ========================================
    // LAPORAN (LENGKAP)
    // ========================================
    Route::prefix('laporan')->name('superadmin.laporan.')->group(function () {
        // Halaman Utama Laporan
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        
        // Laporan Absensi
        Route::get('/absensi', [LaporanController::class, 'absensi'])->name('absensi');
        Route::get('/absensi/pdf', [LaporanController::class, 'exportAbsensiPDF'])->name('absensi.pdf');
        Route::get('/absensi/excel', [LaporanController::class, 'exportAbsensiExcel'])->name('absensi.excel');
        
        // Laporan Cuti/Izin
        Route::get('/cuti-izin', [LaporanController::class, 'cutiIzin'])->name('cutiizin');
        Route::get('/cuti-izin/pdf', [LaporanController::class, 'exportCutiIzinPDF'])->name('cutiizin.pdf');
        Route::get('/cuti-izin/excel', [LaporanController::class, 'exportCutiIzinExcel'])->name('cutiizin.excel');
        
        // Laporan Karyawan
        Route::get('/karyawan', [LaporanController::class, 'karyawan'])->name('karyawan');
        Route::get('/karyawan/pdf', [LaporanController::class, 'exportKaryawanPDF'])->name('karyawan.pdf');
        Route::get('/karyawan/excel', [LaporanController::class, 'exportKaryawanExcel'])->name('karyawan.excel');
        
        // Export Universal (jika ada)
        Route::post('/export', [LaporanController::class, 'export'])->name('export');
    });

    // ========================================
    // PENGATURAN SISTEM
    // ========================================
    Route::prefix('pengaturan')->name('superadmin.pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::put('/jam-kerja', [PengaturanController::class, 'updateJamKerja'])->name('jam-kerja');
        Route::put('/lokasi', [PengaturanController::class, 'updateLokasi'])->name('lokasi');
        Route::put('/general', [PengaturanController::class, 'updateGeneral'])->name('general');
    });

});