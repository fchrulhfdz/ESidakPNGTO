<?php
// database/migrations/2025_12_01_140000_create_umum_keuangan_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hapus tabel jika sudah ada (safe approach)
        Schema::dropIfExists('umum_keuangan_lampirans');
        Schema::dropIfExists('umum_keuangans');
        
        // Create umum_keuangans table
        Schema::create('umum_keuangans', function (Blueprint $table) {
            $table->id();
            $table->text('sasaran_strategis');
            $table->text('indikator_kinerja');
            $table->decimal('target', 5, 2)->nullable();
            $table->string('label_input_1')->nullable();
            $table->integer('input_1')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            // Index untuk performa (tanpa kolom TEXT)
            $table->index(['bulan', 'tahun']);
            $table->index('tahun');
            // Hapus index yang mengandung kolom TEXT
            // $table->index(['sasaran_strategis', 'bulan', 'tahun']); // DIHAPUS
        });

        // Create umum_keuangan_lampirans table
        Schema::create('umum_keuangan_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umum_keuangan_id')
                  ->constrained('umum_keuangans')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path');
            $table->string('original_name');
            $table->bigInteger('file_size');
            $table->string('mime_type')->default('application/pdf');
            $table->timestamps();

            // Index for performance
            $table->index('umum_keuangan_id');
            $table->index('user_id');
            $table->index(['umum_keuangan_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umum_keuangan_lampirans');
        Schema::dropIfExists('umum_keuangans');
    }
};