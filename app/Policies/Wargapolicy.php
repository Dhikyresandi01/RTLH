<?php
 
namespace App\Policies;
 
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
 
        return $user->isAdminKelurahan() && $user->id_wilayah === $warga->id_wilayah;
    }
 
    public function create(User $user): bool
    {
        return $user->isAdminKelurahan();
    }
 
    public function update(User $user, Warga $warga): bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }
 
        return $user->isAdminKelurahan() && $user->id_wilayah === $warga->id_wilayah;
    }
 
    public function delete(User $user, Warga $warga): bool
    {
        // Hanya superadmin yang boleh hapus data warga
        return $user->isSuperadmin();
    }
}