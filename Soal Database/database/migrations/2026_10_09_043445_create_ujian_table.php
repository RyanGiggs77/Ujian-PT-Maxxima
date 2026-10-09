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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id('id_ujian');
            $table->string('nama_ujian', 50);
            $table->foreignId('id_matpel');
            $table->dateTime('tanggal');
            $table->foreign('id_matpel')->references('id_matpel')->on('mata_pelajaran')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
