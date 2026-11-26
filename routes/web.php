<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HunianController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProgramBantuanController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('welcome');


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

    // Master Data & Kelola User (Khusus Superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('program-bantuan', ProgramBantuanController::class)->except(['show', 'create', 'edit']);
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // CMS Kelola Landing Page
        Route::get('landing-page', [LandingPageController::class, 'manage'])->name('landing-page.manage');
        Route::post('landing-page/hero', [LandingPageController::class, 'updateHero'])->name('landing-page.update-hero');
        Route::post('landing-page/profil-kontak', [LandingPageController::class, 'updateProfilKontak'])->name('landing-page.update-profil-kontak');
        Route::post('landing-page/statistik', [LandingPageController::class, 'updateStatistik'])->name('landing-page.update-statistik');
        Route::post('landing-page/publikasi', [LandingPageController::class, 'updatePublikasi'])->name('landing-page.update-publikasi');
        Route::post('landing-page/berita', [LandingPageController::class, 'storeBerita'])->name('landing-page.store-berita');
        Route::post('landing-page/berita/{id}', [LandingPageController::class, 'updateBerita'])->name('landing-page.update-berita');
        Route::delete('landing-page/berita/{id}', [LandingPageController::class, 'deleteBerita'])->name('landing-page.delete-berita');
        Route::post('landing-page/realisasi', [LandingPageController::class, 'updateRealisasi'])->name('landing-page.update-realisasi');
        Route::post('landing-page/faq', [LandingPageController::class, 'storeFaq'])->name('landing-page.store-faq');
        Route::delete('landing-page/faq/{id}', [LandingPageController::class, 'deleteFaq'])->name('landing-page.delete-faq');
        Route::post('landing-page/reset', [LandingPageController::class, 'resetSection'])->name('landing-page.reset');
    });

    // View 2 (Verifikasi Dinas & SPK SAW) & View 4 (Realisasi Bantuan) — Akses Baca Semua Role (Kelurahan View-Only Wilayahnya)
    Route::get('/verifikasi', function (Request $request) {
        return redirect()->route('spk.index', $request->query());
    })->name('verifikasi.index');
    Route::get('/spk', [SpkController::class, 'index'])->name('spk.index');
    Route::get('/realisasi', [RealisasiController::class, 'index'])->name('realisasi.index');

    // Input / Pembaruan Progres Realisasi Fisik (Dapat diinput oleh Kelurahan wilayah terkait, Admin Dinas, & Superadmin)
    Route::post('/realisasi/{id_bantuan}', [RealisasiController::class, 'storeOrUpdate'])->name('realisasi.update');

    // Aksi Mutasi View 2 & Hapus Realisasi — Khusus Superadmin & Admin Dinas
    Route::middleware('role:superadmin,admin_dinas')->group(function () {
        // SPK Simple Additive Weighting & Verifikasi Dinas
        Route::post('/spk/hitung', [SpkController::class, 'hitung'])->name('spk.hitung');
        Route::post('/spk/kriteria', [SpkController::class, 'updateKriteria'])->name('spk.kriteria.update');
        Route::post('/spk/verifikasi/{id_bantuan}', [SpkController::class, 'verifikasiDinas'])->name('spk.verifikasi');

        // Reset / Hapus Data Realisasi
        Route::delete('/realisasi/{id_realisasi}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    });
});


