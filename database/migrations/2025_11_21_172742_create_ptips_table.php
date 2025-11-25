<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ptips', function (Blueprint $table) {
            $table->id();
            $table->text('sasaran_strategis');
            $table->text('indikator_kinerja');

            $table->string('target')->nullable();
            $table->text('rumus')->nullable();

            $table->string('input_1')->nullable();
            $table->string('input_2')->nullable();
            $table->string('label_input_1')->nullable();
            $table->string('label_input_2')->nullable();
            $table->string('realisasi')->nullable();
            $table->string('capaian')->nullable();
            
            // Tambahkan kolom bulan dan tahun
            $table->integer('bulan')->nullable(); // 1-12
            $table->integer('tahun')->nullable(); // 2024, 2025, dst
            
            $table->timestamps();

            // Optional: tambahkan index untuk performa query
            $table->index(['bulan', 'tahun']);
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptips');
    }
};