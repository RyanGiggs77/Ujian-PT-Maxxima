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
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ujian');
            $table->string('nis', 20);
            $table->foreign('nis')->references('nis')->on('siswa')->restrictOnDelete();
            $table->foreign('id_ujian')->references('id_ujian')->on('ujian')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
