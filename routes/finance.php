<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Finance\DashboardController;
use App\Http\Controllers\Finance\GajiController;
use App\Http\Controllers\Finance\PengeluaranController;
use App\Http\Controllers\Finance\PemasukanController;
use App\Http\Controllers\Finance\LaporanKeuanganController;
use App\Http\Controllers\Finance\AnggaranController;
use App\Http\Controllers\Finance\InvoiceController;

// ========================================
// FINANCE ROUTES
// Middleware: auth + karyawan + divisi FINANCE
// ========================================
Route::prefix('finance')->middleware(['auth', 'karyawan', 'check.divisi:FINANCE'])->group(function () {
    
    // ========================================
    // DASHBOARD
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('finance.dashboard');

    // ========================================
    // GAJI KARYAWAN
    // ========================================
    Route::prefix('gaji')->name('finance.gaji.')->group(function () {
        Route::get('/', [GajiController::class, 'index'])->name('index');
        Route::get('/create', [GajiController::class, 'create'])->name('create');
        Route::post('/', [GajiController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GajiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GajiController::class, 'update'])->name('update');
        Route::delete('/{id}', [GajiController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PENGELUARAN PERUSAHAAN
    // ========================================
    Route::prefix('pengeluaran')->name('finance.pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengeluaranController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PEMASUKAN PERUSAHAAN
    // ========================================
    Route::prefix('pemasukan')->name('finance.pemasukan.')->group(function () {
        Route::get('/', [PemasukanController::class, 'index'])->name('index');
        Route::get('/create', [PemasukanController::class, 'create'])->name('create');
        Route::post('/', [PemasukanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PemasukanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PemasukanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PemasukanController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // LAPORAN KEUANGAN
    // ========================================
    Route::prefix('laporan')->name('finance.laporan.')->group(function () {
        Route::get('/', [LaporanKeuanganController::class, 'index'])->name('index');
        Route::get('/bulanan', [LaporanKeuanganController::class, 'bulanan'])->name('bulanan');
        Route::get('/tahunan', [LaporanKeuanganController::class, 'tahunan'])->name('tahunan');
        Route::get('/export', [LaporanKeuanganController::class, 'export'])->name('export');
    });

    // ========================================
    // ANGGARAN (BUDGET)
    // ========================================
    Route::prefix('anggaran')->name('finance.anggaran.')->group(function () {
        Route::get('/', [AnggaranController::class, 'index'])->name('index');
        Route::get('/create', [AnggaranController::class, 'create'])->name('create');
        Route::post('/', [AnggaranController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AnggaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AnggaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [AnggaranController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // INVOICE / FAKTUR
    // ========================================
    Route::prefix('invoice')->name('finance.invoice.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [InvoiceController::class, 'download'])->name('download');
    });

});