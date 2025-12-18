<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipikor', function (Blueprint $table) {
            $table->id();
            
            // Kolom strategi dan indikator
            $table->string('sasaran_strategis', 500);
            $table->string('indikator_kinerja', 500);
            
            // Kolom target dan rumus perhitungan
            $table->decimal('target', 10, 2)->nullable();
            $table->string('rumus', 500)->nullable();
            
            // Kolom input data
            $table->string('label_input_1', 100)->nullable();
            $table->string('label_input_2', 100)->nullable();
            $table->decimal('input_1', 12, 2)->nullable();
            $table->decimal('input_2', 12, 2)->nullable();
            
            // Kolom hasil perhitungan
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('capaian', 10, 2)->nullable();
            $table->string('status_capaian', 50)->nullable();
            $table->string('tipe_input', 20)->nullable(); // TAMBAHKAN DI SINI
            
            // Kolom analisis
            $table->text('hambatan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keberhasilan')->nullable();
            
            // Kolom periode
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            
            // Kolom tambahan khusus tipikor (opsional)
            $table->string('jenis_perkara', 100)->nullable();
            $table->string('tingkat_proses', 50)->nullable();
            
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['bulan', 'tahun']);
            $table->index('sasaran_strategis');
            $table->index('jenis_perkara');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipikor');
    }
};