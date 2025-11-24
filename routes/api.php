<?php
// routes/api.php

use App\Http\Controllers\PerkaraController;
use App\Http\Controllers\KesekretariatanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Perkara routes
    Route::prefix('perkara')->group(function () {
        Route::get('/{bagian}', [PerkaraController::class, 'index']);
        Route::post('/{bagian}', [PerkaraController::class, 'storeApi']);
        Route::post('/calculate', [PerkaraController::class, 'calculateApi']);
    });

    // Kesekretariatan routes
    Route::prefix('kesekretariatan')->group(function () {
        Route::get('/{bagian}', [KesekretariatanController::class, 'index']);
        Route::post('/{bagian}', [KesekretariatanController::class, 'storeApi']);
        Route::post('/calculate', [KesekretariatanController::class, 'calculateApi']);
    });
});