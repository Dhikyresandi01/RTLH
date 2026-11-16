<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HunianController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\WargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ekspor Data Warga ke Excel
    Route::get('/warga/export/excel', [WargaController::class, 'exportExcel'])->name('warga.export');

    // View 3: Calon Penerima Disetujui Dinas (Kontrak route untuk Track 2 & Track 3)
    Route::get('/penerima-disetujui', [WargaController::class, 'disetujui'])->name('warga.disetujui');

    // View 1 (Daftar Usulan: warga.index) & CRUD Terpadu Warga
    Route::resource('warga', WargaController::class);

    // Redirect legacy /Warga -> /warga (mempertahankan nama route 'warga' selama transisi Track 3)
    Route::get('/Warga', function (Request $request) {
        return redirect()->route('warga.index', $request->query(), 301);
    })->name('warga');

    Route::resource('hunian', HunianController::class);
    Route::resource('rumah', RumahController::class);

    // Master Data Jenis Bantuan (Khusus Superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('program-bantuan', ProgramBantuanController::class)->except(['show', 'create', 'edit']);
    });

    // View 2 (Verifikasi Dinas & SPK SAW) & View 4 (Realisasi Bantuan) — Khusus Superadmin & Admin Dinas
    Route::middleware('role:superadmin,admin_dinas')->group(function () {
        // Alias /verifikasi -> redirect ke /spk
        Route::get('/verifikasi', function (Request $request) {
            return redirect()->route('spk.index', $request->query());
        })->name('verifikasi.index');

        // SPK Simple Additive Weighting & Verifikasi Dinas
        Route::get('/spk', [SpkController::class, 'index'])->name('spk.index');
        Route::post('/spk/hitung', [SpkController::class, 'hitung'])->name('spk.hitung');
        Route::post('/spk/kriteria', [SpkController::class, 'updateKriteria'])->name('spk.kriteria.update');
        Route::post('/spk/verifikasi/{id_bantuan}', [SpkController::class, 'verifikasiDinas'])->name('spk.verifikasi');

        // Realisasi & Penyaluran Bantuan
        Route::get('/realisasi', [RealisasiController::class, 'index'])->name('realisasi.index');
        Route::post('/realisasi/{id_bantuan}', [RealisasiController::class, 'storeOrUpdate'])->name('realisasi.update');
        Route::delete('/realisasi/{id_realisasi}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    });
});


