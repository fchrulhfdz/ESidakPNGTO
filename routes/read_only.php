<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerkaraController;
use App\Http\Controllers\KesekretariatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\EvaluasiKerjaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;   


// ==================== DASHBOARD ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ==================== PERKARA ROUTES (HANYA VIEW) ====================
Route::middleware(['auth', 'read_only'])->group(function () {
    // Halaman index perkara
    Route::get('/perdata', [PerkaraController::class, 'showPerdata'])->name('perdata.read_only');
    Route::get('/pidana', [PerkaraController::class, 'showPidana'])->name('pidana.read_only');
    Route::get('/tipikor', [PerkaraController::class, 'showTipikor'])->name('tipikor.read_only');
    Route::get('/phi', [PerkaraController::class, 'showPhi'])->name('phi.read_only');
    Route::get('/hukum', [PerkaraController::class, 'showHukum'])->name('hukum.read_only');
    
    // API untuk mendapatkan data (hanya GET)
    Route::get('/api/get-analisis/{id}', [PerkaraController::class, 'getAnalisisData'])
         ->name('api.get-analisis.read_only');
    Route::get('/api/perkara/indikator-kinerja', [PerkaraController::class, 'getIndikatorKinerja'])
         ->name('api.perkara.indikator.read_only');
    Route::get('/api/perkara/sasaran-detail', [PerkaraController::class, 'getSasaranDetail'])
         ->name('api.perkara.sasaran-detail.read_only');
    
    // Lampiran (hanya view dan download)
    Route::get('/perdata/lampiran', [PerkaraController::class, 'showLampiranPerdata'])->name('perdata.lampiran.show.read_only');
    Route::get('/perdata/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPerdata'])->name('perdata.lampiran.download.read_only');
    
    Route::get('/pidana/lampiran', [PerkaraController::class, 'showLampiranPidana'])->name('pidana.lampiran.show.read_only');
    Route::get('/pidana/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPidana'])->name('pidana.lampiran.download.read_only');
    
    Route::get('/tipikor/lampiran', [PerkaraController::class, 'showLampiranTipikor'])->name('tipikor.lampiran.show.read_only');
    Route::get('/tipikor/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranTipikor'])->name('tipikor.lampiran.download.read_only');
    
    Route::get('/phi/lampiran', [PerkaraController::class, 'showLampiranPhi'])->name('phi.lampiran.show.read_only');
    Route::get('/phi/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPhi'])->name('phi.lampiran.download.read_only');
    
    Route::get('/hukum/lampiran', [PerkaraController::class, 'showLampiranHukum'])->name('hukum.lampiran.show.read_only');
    Route::get('/hukum/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranHukum'])->name('hukum.lampiran.download.read_only');
});

// ==================== KESECKRETARIATAN ROUTES (HANYA VIEW) ====================
Route::middleware(['auth', 'read_only'])->group(function () {
    // Halaman index
    Route::get('/ptip', [KesekretariatanController::class, 'show'])->name('ptip.read_only');
    Route::get('/umum-keuangan', [KesekretariatanController::class, 'show'])->name('umum-keuangan.read_only');
    Route::get('/kepegawaian', [KesekretariatanController::class, 'show'])->name('kepegawaian.read_only');
    
    // API untuk mendapatkan data (hanya GET)
    Route::get('/api/sasaran-strategis/{jenis}', [KesekretariatanController::class, 'getSasaranStrategis'])
         ->name('api.sasaran-strategis.read_only');
    Route::get('/api/{jenis}/by-indikator', [KesekretariatanController::class, 'getByIndikator'])
         ->name('api.by-indikator.read_only');
    Route::get('/api/analisis/{id}', [KesekretariatanController::class, 'getAnalisisData'])
         ->name('api.analisis.read_only');
    
    // Lampiran (hanya view dan download)
    Route::get('/ptip/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('ptip.lampiran.show.read_only');
    Route::get('/ptip/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('ptip.lampiran.download.read_only');
    
    Route::get('/umum-keuangan/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('umum-keuangan.lampiran.show.read_only');
    Route::get('/umum-keuangan/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('umum-keuangan.lampiran.download.read_only');
    
    Route::get('/kepegawaian/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('kepegawaian.lampiran.show.read_only');
    Route::get('/kepegawaian/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('kepegawaian.lampiran.download.read_only');
});

// ==================== LAPORAN ROUTES (FULL ACCESS) ====================
Route::middleware(['auth', 'read_only'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-word', [LaporanController::class, 'cetakWord'])->name('laporan.cetak-word.read_only');
});

// ==================== EVALUASI KERJA ROUTES (HANYA VIEW) ====================
Route::middleware(['auth', 'read_only'])->group(function () {
    Route::get('/evaluasi-kerja', [EvaluasiKerjaController::class, 'index'])->name('evaluasi-kerja.index.read_only');
    Route::get('/evaluasi-kerja/{id}/download', [EvaluasiKerjaController::class, 'download'])->name('evaluasi-kerja.download.read_only');
});