<?php
// database/migrations/2025_11_21_172742_create_ptips_table.php

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
            $table->decimal('target', 5, 2)->nullable();
            $table->string('label_input_1')->nullable();
            $table->integer('input_1')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            // Perbaiki index (tanpa kolom TEXT)
            $table->index(['bulan', 'tahun']);
            $table->index('tahun');
            // Hapus index yang mengandung TEXT jika ada
        });

        Schema::create('ptip_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ptip_id')->constrained('ptips')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path');
            $table->string('original_name');
            $table->bigInteger('file_size');
            $table->string('mime_type')->default('application/pdf');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptip_lampirans');
        Schema::dropIfExists('ptips');
    }
};