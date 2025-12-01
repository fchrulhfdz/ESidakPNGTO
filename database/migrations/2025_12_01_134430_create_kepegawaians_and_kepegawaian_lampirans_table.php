<?php
// database/migrations/2025_12_01_150000_create_kepegawaian_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hapus tabel jika sudah ada (safe approach)
        Schema::dropIfExists('kepegawaian_lampirans');
        Schema::dropIfExists('kepegawaians');
        
        // Create kepegawaians table
        Schema::create('kepegawaians', function (Blueprint $table) {
            $table->id();
            $table->text('sasaran_strategis');
            $table->text('indikator_kinerja');
            $table->decimal('target', 5, 2)->nullable();
            $table->string('label_input_1')->nullable();
            $table->integer('input_1')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            // Index tanpa kolom TEXT
            $table->index(['bulan', 'tahun']);
            $table->index('tahun');
            // $table->index(['sasaran_strategis', 'bulan', 'tahun']); // DIHAPUS
        });

        // Create kepegawaian_lampirans table
        Schema::create('kepegawaian_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepegawaian_id')
                  ->constrained('kepegawaians')
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
            $table->index('kepegawaian_id');
            $table->index('user_id');
            $table->index(['kepegawaian_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepegawaian_lampirans');
        Schema::dropIfExists('kepegawaians');
    }
};