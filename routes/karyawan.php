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
use App\Http\Controllers\Karyawan\WorkspaceController;

// ========================================
// KARYAWAN ROUTES
// Middleware: auth + karyawan
// ========================================
Route::prefix('karyawan')->middleware(['auth', 'karyawan'])->group(function () {
    
    // ========================================
    // DASHBOARD
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('karyawan.dashboard');

    // ========================================
    // ABSENSI CHECK-IN/CHECK-OUT
    // ========================================
    Route::prefix('absensi')->name('karyawan.absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::post('/check-in', [AbsensiController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [AbsensiController::class, 'checkOut'])->name('checkout');
        Route::get('/today', [AbsensiController::class, 'today'])->name('today');
    });

    // ========================================
    // REKAP ABSENSI
    // ========================================
    Route::prefix('rekap')->name('karyawan.rekap.')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('index');
        Route::get('/harian', [RekapController::class, 'harian'])->name('harian');
        Route::get('/mingguan', [RekapController::class, 'mingguan'])->name('mingguan');
        Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
    });

    // ========================================
    // PENGAJUAN CUTI/IZIN - ROUTE UTAMA
    // ========================================
    Route::prefix('cuti-izin')->name('karyawan.cutiizin.')->group(function () {
        Route::get('/', [CutiIzinController::class, 'index'])->name('index');
        Route::get('/create', [CutiIzinController::class, 'create'])->name('create');
        Route::post('/', [CutiIzinController::class, 'store'])->name('store');
        Route::get('/{id}', [CutiIzinController::class, 'show'])->name('show');
        Route::delete('/{id}', [CutiIzinController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // ALIAS ROUTE UNTUK IZIN (untuk AJAX dari halaman absensi)
    // ========================================
    Route::prefix('izin')->name('karyawan.izin.')->group(function () {
        Route::get('/', [CutiIzinController::class, 'index'])->name('index');
        Route::get('/create', [CutiIzinController::class, 'create'])->name('create');
        Route::post('/store', [CutiIzinController::class, 'storeIzin'])->name('store'); // ✅ UBAH JADI storeIzin
        Route::get('/{id}', [CutiIzinController::class, 'show'])->name('show');
        Route::delete('/{id}', [CutiIzinController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // ALIAS ROUTE UNTUK CUTI (untuk AJAX dari halaman absensi)
    // ========================================
    Route::prefix('cuti')->name('karyawan.cuti.')->group(function () {
        Route::get('/', [CutiIzinController::class, 'index'])->name('index');
        Route::get('/create', [CutiIzinController::class, 'create'])->name('create');
        Route::post('/store', [CutiIzinController::class, 'storeCuti'])->name('store'); // ✅ UBAH JADI storeCuti
        Route::get('/{id}', [CutiIzinController::class, 'show'])->name('show');
        Route::delete('/{id}', [CutiIzinController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // SURAT/DOKUMEN - LENGKAP
    // ========================================
    Route::prefix('surat')->name('karyawan.surat.')->group(function () {
        Route::get('/', [SuratController::class, 'index'])->name('index');
        Route::get('/create', [SuratController::class, 'create'])->name('create');
        Route::post('/', [SuratController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SuratController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SuratController::class, 'update'])->name('update');
        Route::get('/{id}/download', [SuratController::class, 'download'])->name('download');
        Route::delete('/{id}', [SuratController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [SuratController::class, 'show'])->name('show');
    });

    // ========================================
    // SERVER LOG - CRUD LENGKAP
    // ========================================
    Route::prefix('server-log')->name('karyawan.serverlog.')->group(function () {
        Route::get('/', [ServerLogController::class, 'index'])->name('index');
        Route::get('/create', [ServerLogController::class, 'create'])->name('create');
        Route::post('/', [ServerLogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ServerLogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ServerLogController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServerLogController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ServerLogController::class, 'show'])->name('show');
    });

    // ========================================
    // WORKSPACE (BARU) ✨
    // ========================================
    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('karyawan.workspace.index');

    // ========================================
    // PENGUMUMAN
    // ========================================
    Route::prefix('pengumuman')->name('karyawan.pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/{id}', [PengumumanController::class, 'show'])->name('show');
    });

    // ========================================
    // PROFIL
    // ========================================
    Route::prefix('profil')->name('karyawan.profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
        Route::post('/foto', [ProfilController::class, 'updateFoto'])->name('foto');
    });

});