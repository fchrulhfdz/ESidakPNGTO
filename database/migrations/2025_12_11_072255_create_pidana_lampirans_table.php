<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pidana_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pidana_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path');
            $table->string('original_name');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pidana_lampirans');
    }
};