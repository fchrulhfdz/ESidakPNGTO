<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipikor', function (Blueprint $table) {
            $table->id();
            $table->string('sasaran_strategis');
            $table->string('indikator_kinerja');
            $table->string('target')->nullable();
            $table->string('rumus')->nullable();
            $table->integer('input_1')->nullable();
            $table->integer('input_2')->nullable();
            $table->integer('realisasi')->nullable();
            $table->string('capaian')->nullable();
            $table->integer('bulan')->nullable(); // 1-12
            $table->integer('tahun')->nullable(); // 2024, 2025, dst
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipikor');
    }
};
