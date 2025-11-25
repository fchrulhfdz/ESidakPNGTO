<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerkaraController;
use App\Http\Controllers\KesekretariatanController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// ==================== PERKARA ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/perdata', [PerkaraController::class, 'showPerdata'])->name('perdata');
    Route::get('/pidana', [PerkaraController::class, 'showPidana'])->name('pidana');
    Route::get('/tipikor', [PerkaraController::class, 'showTipikor'])->name('tipikor');
    Route::get('/phi', [PerkaraController::class, 'showPHI'])->name('phi');
    Route::get('/hukum', [PerkaraController::class, 'showHukum'])->name('hukum');
    
    // CRUD Routes untuk Perkara
    Route::post('/perkara/store', [PerkaraController::class, 'store'])->name('store.perkara');
    Route::put('/perkara/{id}', [PerkaraController::class, 'update'])->name('perkara.update');
    Route::delete('/perkara/{id}', [PerkaraController::class, 'destroy'])->name('perkara.destroy');
    
    // Route untuk kalkulasi
    Route::post('/perkara/calculate', [PerkaraController::class, 'calculate'])->name('calculate.perkara');
});

// ==================== KESECKRETARIATAN ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes untuk menampilkan halaman
    Route::get('/ptip', [KesekretariatanController::class, 'show'])->name('ptip');
    Route::get('/umum-keuangan', [KesekretariatanController::class, 'show'])->name('umum-keuangan');
    Route::get('/kepegawaian', [KesekretariatanController::class, 'show'])->name('kepegawaian');
    
    // CRUD Routes untuk Kesekretariatan
    Route::post('/ptip/store', [KesekretariatanController::class, 'store'])->name('store.ptip');
    Route::post('/umum-keuangan/store', [KesekretariatanController::class, 'store'])->name('store.umum-keuangan');
    Route::post('/kepegawaian/store', [KesekretariatanController::class, 'store'])->name('store.kepegawaian');
    
    // Update routes
    Route::put('/ptip/{id}', [KesekretariatanController::class, 'update'])->name('ptip.update');
    Route::put('/umum-keuangan/{id}', [KesekretariatanController::class, 'update'])->name('umum-keuangan.update');
    Route::put('/kepegawaian/{id}', [KesekretariatanController::class, 'update'])->name('kepegawaian.update');
    
    // Delete routes  
    Route::delete('/ptip/{id}', [KesekretariatanController::class, 'destroy'])->name('ptip.destroy');
    Route::delete('/umum-keuangan/{id}', [KesekretariatanController::class, 'destroy'])->name('umum-keuangan.destroy');
    Route::delete('/kepegawaian/{id}', [KesekretariatanController::class, 'destroy'])->name('kepegawaian.destroy');
    
    // Route untuk kalkulasi kesekretariatan
    Route::post('/kesekretariatan/calculate', [KesekretariatanController::class, 'calculate'])->name('calculate.kesekretariatan');
});

// ==================== LAPORAN ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
});

require __DIR__.'/auth.php';