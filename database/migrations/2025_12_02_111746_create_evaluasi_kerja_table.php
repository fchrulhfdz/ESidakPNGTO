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
        Schema::create('evaluasi_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('bagian'); // perdata, pidana, tipikor, phi, hukum, ptip, kepegawaian, umum_keuangan
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('judul');
            $table->string('tahun');
            $table->string('bulan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_kerja');
    }
};