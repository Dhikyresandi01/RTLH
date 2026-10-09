<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Superadmin: kelola user, master data, akses semua laporan
        Gate::define('kelola-user', fn ($user) => $user->isSuperadmin());
        Gate::define('kelola-master-data', fn ($user) => $user->isSuperadmin());
        Gate::define('lihat-semua-laporan', fn ($user) => $user->isSuperadmin());
 
        // Admin perusahaan: verifikasi usulan, catat realisasi, buat laporan
        Gate::define('verifikasi-bantuan', fn ($user) => $user->isSuperadmin() || $user->isAdminDinas());
        Gate::define('catat-realisasi', fn ($user) => $user->isSuperadmin() || $user->isAdminDinas());
        Gate::define('buat-laporan', fn ($user) => $user->isSuperadmin() || $user->isAdminDinas());
 
        // Admin kelurahan: input data warga, ajukan usulan bantuan
        Gate::define('input-warga', fn ($user) => $user->isAdminKelurahan());
        Gate::define('ajukan-bantuan', fn ($user) => $user->isAdminKelurahan());
    }
}
