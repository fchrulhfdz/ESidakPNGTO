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
Route::middleware(['auth'])->group(function () {
    // Route khusus untuk setiap jenis perkara
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
Route::middleware(['auth'])->group(function () {
    Route::get('/ptip', [KesekretariatanController::class, 'showPTIP'])->name('ptip');
    Route::get('/umum-keuangan', [KesekretariatanController::class, 'showUmumKeuangan'])->name('umum-keuangan');
    Route::get('/kepegawaian', [KesekretariatanController::class, 'showKepegawaian'])->name('kepegawaian');
    
    // CRUD Routes untuk Kesekretariatan
    Route::post('/kesekretariatan/store', [KesekretariatanController::class, 'store'])->name('store.kesekretariatan');
    Route::put('/kesekretariatan/{id}', [KesekretariatanController::class, 'update'])->name('kesekretariatan.update');
    Route::delete('/kesekretariatan/{id}', [KesekretariatanController::class, 'destroy'])->name('kesekretariatan.destroy');
    
    // Route untuk kalkulasi kesekretariatan
    Route::post('/kesekretariatan/calculate', [KesekretariatanController::class, 'calculate'])->name('calculate.kesekretariatan');
});

// ==================== LAPORAN ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
});

require __DIR__.'/auth.php';