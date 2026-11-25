<?php

namespace App\Policies;

use App\Models\Bantuan;
use App\Models\User;
use App\Models\Warga;

class WargaPolicy
{
    public function viewAny(User $user): bool
    {
        // Semua role boleh melihat daftar; pembatasan per wilayah dilakukan
        // di query controller lewat Warga::visibleTo($user).
        return true;
    }

    public function view(User $user, Warga $warga): bool
    {
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return true;
        }

        return $user->isAdminKelurahan() && $this->isSameWilayah($user, $warga);
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin() || $user->isAdminKelurahan();
    }

    public function update(User $user, Warga $warga): bool
    {
        // 1. Superadmin & Admin Dinas memiliki akses penuh untuk mengedit data warga pada semua status
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return true;
        }

        // 2. Admin Kelurahan hanya boleh mengedit warga di wilayahnya sendiri
        if (! $user->isAdminKelurahan() || ! $this->isSameWilayah($user, $warga)) {
            return false;
        }

        $bantuan = $warga->relationLoaded('bantuanAktif')
            ? $warga->bantuanAktif
            : $warga->bantuanAktif()->first();

        if (! $bantuan) {
            return true;
        }

        // 3. Admin Kelurahan hanya boleh mengedit saat status 0 (Diajukan), 1 (Diverifikasi), atau 3 (Revisi)
        // Data yang sudah divalidasi/disetujui dinas (2), ditolak (4), atau direalisasikan (5) terkunci untuk Kelurahan
        return in_array((int) $bantuan->status_bantuan, Bantuan::STATUS_EDITABLE_KELURAHAN, true);
    }

    public function delete(User $user, Warga $warga): bool
    {
        // Hanya superadmin yang boleh hapus data warga
        return $user->isSuperadmin();
    }

    protected function isSameWilayah(User $user, Warga $warga): bool
    {
        return $user->id_wilayah !== null
            && $warga->id_wilayah !== null
            && (int) $user->id_wilayah === (int) $warga->id_wilayah;
    }
}
