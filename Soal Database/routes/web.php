<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UjianController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LaporanController::class, 'index'])->name('home');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::resource('siswa', SiswaController::class)->except('show')->parameters(['siswa' => 'siswa']);
Route::resource('mata-pelajaran', MataPelajaranController::class)->except('show')->parameters(['mata-pelajaran' => 'mataPelajaran']);
Route::resource('ujian', UjianController::class)->except('show')->parameters(['ujian' => 'ujian']);
Route::resource('peserta', PesertaController::class)->except('show')->parameters(['peserta' => 'peserta']);
