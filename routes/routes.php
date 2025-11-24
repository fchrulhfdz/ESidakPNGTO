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

// SEMUA ROUTE DENGAN AUTH SAJA - Authorization di handle oleh Controller
Route::middleware(['auth'])->group(function () {
    // Perkara
    Route::get('/perdata', [PerkaraController::class, 'show'])->name('perdata');
    Route::get('/pidana', [PerkaraController::class, 'show'])->name('pidana');
    Route::get('/tipikor', [PerkaraController::class, 'show'])->name('tipikor');
    Route::get('/phi', [PerkaraController::class, 'show'])->name('phi');
    Route::get('/hukum', [PerkaraController::class, 'show'])->name('hukum');
    
    // Kesekretariatan
    Route::get('/ptip', [KesekretariatanController::class, 'show'])->name('ptip');
    Route::get('/umum-keuangan', [KesekretariatanController::class, 'show'])->name('umum-keuangan');
    Route::get('/kepegawaian', [KesekretariatanController::class, 'show'])->name('kepegawaian');
    
    // Common routes
    Route::post('/calculate', [PerkaraController::class, 'calculate'])->name('calculate');
    Route::post('/store-perkara', [PerkaraController::class, 'store'])->name('store.perkara');
    Route::post('/store-kesekretariatan', [KesekretariatanController::class, 'store'])->name('store.kesekretariatan');
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
});

require __DIR__.'/auth.php';