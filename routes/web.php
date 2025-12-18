<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerkaraController;
use App\Http\Controllers\KesekretariatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\EvaluasiKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


    Route::get('/test-controller-perdata', [PerkaraController::class, 'showPerdata'])
    ->middleware(['auth'])
    ->name('test.controller.perdata');
// ==================== PERKARA ROUTES ====================
// SEMUA ROUTES PERKARA DI SATU GROUP DENGAN MIDDLEWARE ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes untuk menampilkan halaman perkara - SEMUA ROLE BISA LIHAT (GET)
    Route::get('/perdata', [PerkaraController::class, 'showPerdata'])->name('perdata');
    Route::get('/pidana', [PerkaraController::class, 'showPidana'])->name('pidana');
    Route::get('/tipikor', [PerkaraController::class, 'showTipikor'])->name('tipikor');
    Route::get('/phi', [PerkaraController::class, 'showPhi'])->name('phi');
    Route::get('/hukum', [PerkaraController::class, 'showHukum'])->name('hukum');
    
    // CRUD Routes untuk Perkara - HANYA ADMIN BUKAN READ_ONLY (POST/PUT/DELETE)
    Route::post('/perkara/store', [PerkaraController::class, 'store'])->name('store.perkara');
    Route::put('/perkara/{id}', [PerkaraController::class, 'update'])->name('perkara.update');
    Route::delete('/perkara/{id}', [PerkaraController::class, 'destroy'])->name('perkara.destroy');
    
    // API Routes untuk Perkara
    Route::post('/perkara/calculate-dua-input', [PerkaraController::class, 'calculateDuaInput'])->name('calculate.dua-input');
    Route::post('/perkara/calculate-satu-input', [PerkaraController::class, 'calculateSatuInput'])->name('calculate.satu-input');
    
    // API untuk mendapatkan data edit (GET - semua role)
    Route::get('/get-edit-data/{jenis}/{id}', [PerkaraController::class, 'getEditData'])->name('get.edit.data.perkara');
    Route::get('/api/get-analisis/{id}', [PerkaraController::class, 'getAnalisisData'])->name('api.get-analisis');
    Route::get('/api/perkara/indikator-kinerja', [PerkaraController::class, 'getIndikatorKinerja'])->name('api.perkara.indikator');
    Route::get('/api/perkara/sasaran-detail', [PerkaraController::class, 'getSasaranDetail'])->name('api.perkara.sasaran-detail');
    
    // ============ ROUTES UNTUK LAMPIRAN SEMUA JENIS ============
    
    // Routes untuk Lampiran Perdata - GET (semua role)
    Route::get('/perdata/lampiran', [PerkaraController::class, 'showLampiranPerdata'])->name('perdata.lampiran.show');
    Route::get('/perdata/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPerdata'])->name('perdata.lampiran.download');
    
    // CRUD Lampiran Perdata - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/perdata/lampiran/upload', [PerkaraController::class, 'storeLampiranPerdata'])->name('perdata.lampiran.store');
    Route::put('/perdata/lampiran/{id}', [PerkaraController::class, 'updateLampiranPerdata'])->name('perdata.lampiran.update');
    Route::delete('/perdata/lampiran/{id}', [PerkaraController::class, 'destroyLampiranPerdata'])->name('perdata.lampiran.destroy');
    
    // Routes untuk Lampiran Pidana - GET (semua role)
    Route::get('/pidana/lampiran', [PerkaraController::class, 'showLampiranPidana'])->name('pidana.lampiran.show');
    Route::get('/pidana/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPidana'])->name('pidana.lampiran.download');
    
    // CRUD Lampiran Pidana - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/pidana/lampiran/upload', [PerkaraController::class, 'storeLampiranPidana'])->name('pidana.lampiran.store');
    Route::put('/pidana/lampiran/{id}', [PerkaraController::class, 'updateLampiranPidana'])->name('pidana.lampiran.update');
    Route::delete('/pidana/lampiran/{id}', [PerkaraController::class, 'destroyLampiranPidana'])->name('pidana.lampiran.destroy');
    
    // Routes untuk Lampiran Tipikor - GET (semua role)
    Route::get('/tipikor/lampiran', [PerkaraController::class, 'showLampiranTipikor'])->name('tipikor.lampiran.show');
    Route::get('/tipikor/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranTipikor'])->name('tipikor.lampiran.download');
    
    // CRUD Lampiran Tipikor - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/tipikor/lampiran/upload', [PerkaraController::class, 'storeLampiranTipikor'])->name('tipikor.lampiran.store');
    Route::put('/tipikor/lampiran/{id}', [PerkaraController::class, 'updateLampiranTipikor'])->name('tipikor.lampiran.update');
    Route::delete('/tipikor/lampiran/{id}', [PerkaraController::class, 'destroyLampiranTipikor'])->name('tipikor.lampiran.destroy');
    
    // Routes untuk Lampiran PHI - GET (semua role)
    Route::get('/phi/lampiran', [PerkaraController::class, 'showLampiranPhi'])->name('phi.lampiran.show');
    Route::get('/phi/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranPhi'])->name('phi.lampiran.download');
    
    // CRUD Lampiran PHI - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/phi/lampiran/upload', [PerkaraController::class, 'storeLampiranPhi'])->name('phi.lampiran.store');
    Route::put('/phi/lampiran/{id}', [PerkaraController::class, 'updateLampiranPhi'])->name('phi.lampiran.update');
    Route::delete('/phi/lampiran/{id}', [PerkaraController::class, 'destroyLampiranPhi'])->name('phi.lampiran.destroy');
    
    // Routes untuk Lampiran Hukum - GET (semua role)
    Route::get('/hukum/lampiran', [PerkaraController::class, 'showLampiranHukum'])->name('hukum.lampiran.show');
    Route::get('/hukum/lampiran/{id}/download', [PerkaraController::class, 'downloadLampiranHukum'])->name('hukum.lampiran.download');
    
    // CRUD Lampiran Hukum - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/hukum/lampiran/upload', [PerkaraController::class, 'storeLampiranHukum'])->name('hukum.lampiran.store');
    Route::put('/hukum/lampiran/{id}', [PerkaraController::class, 'updateLampiranHukum'])->name('hukum.lampiran.update');
    Route::delete('/hukum/lampiran/{id}', [PerkaraController::class, 'destroyLampiranHukum'])->name('hukum.lampiran.destroy');
});

// ==================== KESECKRETARIATAN ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    // Routes untuk menampilkan halaman - GET (semua role)
    Route::get('/ptip', [KesekretariatanController::class, 'show'])->name('ptip');
    Route::get('/umum-keuangan', [KesekretariatanController::class, 'show'])->name('umum-keuangan');
    Route::get('/kepegawaian', [KesekretariatanController::class, 'show'])->name('kepegawaian');
    
    // CRUD Routes untuk Kesekretariatan - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/ptip/store', [KesekretariatanController::class, 'store'])->name('store.ptip');
    Route::post('/umum-keuangan/store', [KesekretariatanController::class, 'store'])->name('store.umum-keuangan');
    Route::post('/kepegawaian/store', [KesekretariatanController::class, 'store'])->name('store.kepegawaian');
    
    // Update routes - HANYA ADMIN (PUT)
    Route::put('/ptip/{id}', [KesekretariatanController::class, 'update'])->name('ptip.update');
    Route::put('/umum-keuangan/{id}', [KesekretariatanController::class, 'update'])->name('umum-keuangan.update');
    Route::put('/kepegawaian/{id}', [KesekretariatanController::class, 'update'])->name('kepegawaian.update');
    
    // Delete routes - HANYA ADMIN (DELETE)
    Route::delete('/ptip/{id}', [KesekretariatanController::class, 'destroy'])->name('ptip.destroy');
    Route::delete('/umum-keuangan/{id}', [KesekretariatanController::class, 'destroy'])->name('umum-keuangan.destroy');
    Route::delete('/kepegawaian/{id}', [KesekretariatanController::class, 'destroy'])->name('kepegawaian.destroy');
    
    // API untuk perhitungan capaian real-time - HANYA ADMIN (POST)
    Route::post('/calculate-capaian', [KesekretariatanController::class, 'calculateCapaianApi'])->name('calculate.capaian');
    
    // API untuk mendapatkan data - GET (semua role)
    Route::get('/api/sasaran-strategis/{jenis}', [KesekretariatanController::class, 'getSasaranStrategis'])->name('api.sasaran-strategis');
    Route::get('/api/{jenis}/by-indikator', [KesekretariatanController::class, 'getByIndikator'])->name('api.by-indikator');
    Route::get('/api/analisis/{id}', [KesekretariatanController::class, 'getAnalisisData'])->name('api.analisis');
    
    // ============ ROUTES UNTUK LAMPIRAN ============
    // Routes untuk Lampiran PTIP - GET (semua role)
    Route::get('/ptip/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('ptip.lampiran.show');
    Route::get('/ptip/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('ptip.lampiran.download');
    
    // CRUD Lampiran PTIP - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/ptip/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('ptip.lampiran.store');
    Route::put('/ptip/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('ptip.lampiran.update');
    Route::delete('/ptip/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('ptip.lampiran.destroy');
    
    // Routes untuk Lampiran Umum Keuangan - GET (semua role)
    Route::get('/umum-keuangan/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('umum-keuangan.lampiran.show');
    Route::get('/umum-keuangan/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('umum-keuangan.lampiran.download');
    
    // CRUD Lampiran Umum Keuangan - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/umum-keuangan/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('umum-keuangan.lampiran.store');
    Route::put('/umum-keuangan/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('umum-keuangan.lampiran.update');
    Route::delete('/umum-keuangan/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('umum-keuangan.lampiran.destroy');
    
    // Routes untuk Lampiran Kepegawaian - GET (semua role)
    Route::get('/kepegawaian/lampiran', [KesekretariatanController::class, 'showLampiran'])->name('kepegawaian.lampiran.show');
    Route::get('/kepegawaian/lampiran/{id}/download', [KesekretariatanController::class, 'downloadLampiran'])->name('kepegawaian.lampiran.download');
    
    // CRUD Lampiran Kepegawaian - HANYA ADMIN (POST/PUT/DELETE)
    Route::post('/kepegawaian/lampiran', [KesekretariatanController::class, 'storeLampiran'])->name('kepegawaian.lampiran.store');
    Route::put('/kepegawaian/lampiran/{id}', [KesekretariatanController::class, 'updateLampiran'])->name('kepegawaian.lampiran.update');
    Route::delete('/kepegawaian/lampiran/{id}', [KesekretariatanController::class, 'destroyLampiran'])->name('kepegawaian.lampiran.destroy');
});

// ==================== LAPORAN ROUTES ====================
// LAPORAN UNTUK SEMUA ROLE (termasuk read_only)
Route::middleware(['auth'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak-word', [LaporanController::class, 'cetakWord'])->name('laporan.cetak-word');
});

// ==================== EVALUASI KERJA ROUTES ====================
Route::middleware(['auth', 'admin'])->group(function () {
    // INDEX - GET (semua role termasuk read_only)
    Route::get('/evaluasi-kerja', [EvaluasiKerjaController::class, 'index'])->name('evaluasi-kerja.index');
    Route::get('/evaluasi-kerja/{id}/download', [EvaluasiKerjaController::class, 'download'])->name('evaluasi-kerja.download');
    
    // CRUD - HANYA ADMIN (POST/DELETE)
    Route::post('/evaluasi-kerja', [EvaluasiKerjaController::class, 'store'])->name('evaluasi-kerja.store');
    Route::delete('/evaluasi-kerja/{id}', [EvaluasiKerjaController::class, 'destroy'])->name('evaluasi-kerja.destroy');
});

require __DIR__.'/auth.php';