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
    
    Route::post('/perkara/store', [PerkaraController::class, 'store'])->name('store.perkara');
    Route::put('/perkara/{id}', [PerkaraController::class, 'update'])->name('perkara.update');
    Route::delete('/perkara/{id}', [PerkaraController::class, 'destroy'])->name('perkara.destroy');
    
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
    
    // Routes untuk Lampiran PTIP
    Route::get('/ptip/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('ptip.lampiran.show');
    Route::post('/ptip/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('ptip.lampiran.store');
    Route::put('/ptip/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('ptip.lampiran.update');
    Route::delete('/ptip/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('ptip.lampiran.destroy');
    Route::get('/ptip/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('ptip.lampiran.download');
    
    // Routes untuk Lampiran Umum Keuangan
    Route::get('/umum-keuangan/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('umum-keuangan.lampiran.show');
    Route::post('/umum-keuangan/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('umum-keuangan.lampiran.store');
    Route::put('/umum-keuangan/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('umum-keuangan.lampiran.update');
    Route::delete('/umum-keuangan/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('umum-keuangan.lampiran.destroy');
    Route::get('/umum-keuangan/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('umum-keuangan.lampiran.download');
    
    // Routes untuk Lampiran Kepegawaian
    Route::get('/kepegawaian/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('kepegawaian.lampiran.show');
    Route::post('/kepegawaian/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('kepegawaian.lampiran.store');
    Route::put('/kepegawaian/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('kepegawaian.lampiran.update');
    Route::delete('/kepegawaian/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('kepegawaian.lampiran.destroy');
    Route::get('/kepegawaian/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('kepegawaian.lampiran.download');
});

// ==================== LAPORAN ROUTES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-word', [LaporanController::class, 'cetakWord'])->name('laporan.cetak-word');
});

require __DIR__.'/auth.php';