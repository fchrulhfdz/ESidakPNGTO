<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pidanas', function (Blueprint $table) {
            $table->id();
            // Ubah dari text() menjadi string() dengan batas panjang
            $table->string('sasaran_strategis', 500);
            $table->string('indikator_kinerja', 500);
            
            // Kolom target dan rumus
            $table->decimal('target', 8, 2)->nullable();
            $table->string('rumus', 500)->nullable();
            
            // Kolom input dan label
            $table->string('label_input_1', 100)->nullable();
            $table->string('label_input_2', 100)->nullable();
            $table->integer('input_1')->nullable();
            $table->integer('input_2')->nullable();
            
            // Kolom hasil perhitungan
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('capaian', 10, 2)->nullable();
            $table->string('status_capaian', 50)->nullable();
            $table->string('tipe_input', 20)->nullable(); // TAMBAHKAN DI SINI
            
            // Kolom analisis baru - tetap menggunakan text() karena tidak di-index
            $table->text('hambatan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keberhasilan')->nullable();
            
            // Kolom periode
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['bulan', 'tahun']);
            // Index bisa dibuat karena sekarang tipe data string, bukan text
            $table->index('sasaran_strategis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pidanas');
    }
};