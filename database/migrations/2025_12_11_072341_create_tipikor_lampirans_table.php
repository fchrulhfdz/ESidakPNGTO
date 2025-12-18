<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipikor_lampirans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipikor_id')->nullable(); // Ubah menjadi nullable dulu
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_size');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            // HAPUS foreign key constraint untuk sementara
            // $table->foreign('tipikor_id')->references('id')->on('tipikors')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipikor_lampirans');
    }
};