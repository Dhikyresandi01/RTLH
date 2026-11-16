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
        return $user->isSuperadmin() || $user->isAdminKelurahan() || $user->isAdminDinas();
    }

    public function update(User $user, Warga $warga): bool
    {
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return true;
        }

        if (! $user->isAdminKelurahan() || ! $this->isSameWilayah($user, $warga)) {
            return false;
        }

        $bantuan = $warga->relationLoaded('bantuanAktif')
            ? $warga->bantuanAktif
            : $warga->bantuanAktif()->first();

        if (! $bantuan) {
            return true;
        }

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
