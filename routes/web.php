<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HunianController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ekspor Data Warga ke Excel
    Route::get('/warga/export/excel', [WargaController::class, 'exportExcel'])->name('warga.export');

    Route::resource('warga', WargaController::class);
    Route::get('/Warga', [WargaController::class, 'index'])->name('warga');

    Route::resource('hunian', HunianController::class);
    Route::resource('rumah', RumahController::class);

    // Master Data Jenis Bantuan
    Route::resource('program-bantuan', ProgramBantuanController::class)->except(['show', 'create', 'edit']);

    // SPK Simple Additive Weighting & Verifikasi Dinas
    Route::get('/spk', [SpkController::class, 'index'])->name('spk.index');
    Route::post('/spk/hitung', [SpkController::class, 'hitung'])->name('spk.hitung');
    Route::post('/spk/kriteria', [SpkController::class, 'updateKriteria'])->name('spk.kriteria.update');
    Route::post('/spk/verifikasi/{id_bantuan}', [SpkController::class, 'verifikasiDinas'])->name('spk.verifikasi');

    // Realisasi & Penyaluran Bantuan (Admin Dinas & Super Admin)
    Route::get('/realisasi', [RealisasiController::class, 'index'])->name('realisasi.index');
    Route::post('/realisasi/{id_bantuan}', [RealisasiController::class, 'storeOrUpdate'])->name('realisasi.update');
    Route::delete('/realisasi/{id_realisasi}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
});


