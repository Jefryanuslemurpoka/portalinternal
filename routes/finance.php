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
    // GAJI KARYAWAN (UPDATED - SISTEM KOMPLEKS)
    // ========================================
    Route::prefix('gaji')->name('finance.gaji.')->group(function () {
        
        // Dashboard & Index
        Route::get('/', [GajiController::class, 'index'])->name('index');
        Route::get('/dashboard', [GajiController::class, 'dashboard'])->name('dashboard');
        
        // Generate Slip Gaji
        Route::get('/create', [GajiController::class, 'create'])->name('create');
        Route::post('/generate', [GajiController::class, 'generate'])->name('generate');
        Route::post('/generate-all', [GajiController::class, 'generateAll'])->name('generate-all');
        
        // Master Gaji Routes
        Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', [GajiController::class, 'masterIndex'])->name('index');
        Route::get('/create', [GajiController::class, 'masterCreate'])->name('create');
        Route::post('/', [GajiController::class, 'masterStore'])->name('store');
        Route::get('/{id}/edit', [GajiController::class, 'masterEdit'])->name('edit');
        Route::put('/{id}', [GajiController::class, 'masterUpdate'])->name('update');
        Route::delete('/{id}', [GajiController::class, 'masterDestroy'])->name('destroy'); // ← TAMBAHKAN INI
        });
        
        // CRUD Slip Gaji (TARUH SETELAH MASTER)
        Route::get('/{id}', [GajiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [GajiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GajiController::class, 'update'])->name('update');
        Route::delete('/{id}', [GajiController::class, 'destroy'])->name('destroy');
        
        // Workflow Actions
        Route::post('/{id}/submit', [GajiController::class, 'submit'])->name('submit');
        Route::post('/{id}/approve', [GajiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [GajiController::class, 'reject'])->name('reject');
        Route::post('/{id}/mark-paid', [GajiController::class, 'markAsPaid'])->name('mark-paid');
        
        // Export
        Route::get('/{id}/pdf', [GajiController::class, 'exportPdf'])->name('pdf');
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

        // ========================================
    // INVOICE (STATIC UI ONLY - TEMPORARY)
    // ========================================
    Route::view('/invoice-edit', 'finance.invoice.edit')->name('finance.invoice.edit.static');
    Route::view('/invoice-show', 'finance.invoice.show')->name('finance.invoice.show.static');

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