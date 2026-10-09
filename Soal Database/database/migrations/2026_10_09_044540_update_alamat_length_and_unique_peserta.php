<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasDuplicateParticipants = DB::table('peserta')
            ->select('id_ujian', 'nis')
            ->groupBy('id_ujian', 'nis')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicateParticipants) {
            throw new RuntimeException('Terdapat peserta duplikat untuk pasangan id_ujian dan nis. Periksa data duplikat sebelum menjalankan migration kembali.');
        }

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('alamat', 100)->change();
        });

        Schema::table('peserta', function (Blueprint $table) {
            $table->unique(['id_ujian', 'nis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropUnique(['id_ujian', 'nis']);
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('alamat', 50)->change();
        });
    }
};
