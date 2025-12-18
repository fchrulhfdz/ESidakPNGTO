<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perdata_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perdata_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path');
            $table->string('original_name');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perdata_lampirans');
    }
};