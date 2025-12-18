<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kepegawaians', function (Blueprint $table) {
            $table->id();
            $table->text('sasaran_strategis');
            $table->text('indikator_kinerja');
            $table->decimal('target', 5, 2)->nullable();
            $table->string('label_input_1')->nullable();
            $table->integer('input_1')->nullable();
            
            // Kolom capaian
            $table->decimal('capaian', 10, 2)->nullable();
            $table->string('status_capaian', 20)->nullable();
            
            // Kolom analisis
            $table->text('hambatan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('keberhasilan')->nullable();
            
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            // Index
            $table->index(['bulan', 'tahun']);
            $table->index('tahun');
        });

        Schema::create('kepegawaian_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kepegawaian_id')->constrained('kepegawaians')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path');
            $table->string('original_name');
            $table->bigInteger('file_size');
            $table->string('mime_type')->default('application/pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepegawaian_lampirans');
        Schema::dropIfExists('kepegawaians');
    }
};