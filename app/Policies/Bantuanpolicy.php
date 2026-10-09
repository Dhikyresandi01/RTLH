<?php
 
namespace App\Policies;
 
use App\Models\Bantuan;
use App\Models\User;
 
class BantuanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
 
    public function view(User $user, Bantuan $bantuan): bool
    {
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return true;
        }
 
        return $user->isAdminKelurahan() && $user->id_wilayah === $bantuan->warga->id_wilayah;
    }
 
    public function create(User $user): bool
    {
        return $user->isAdminKelurahan();
    }
 
    public function verify(User $user): bool
    {
        // Approve/reject/minta revisi usulan bantuan
        return $user->isSuperadmin() || $user->isAdminDinas();
    }
 
    public function recordRealization(User $user): bool
    {
        // Mencatat realisasi penyaluran bantuan
        return $user->isSuperadmin() || $user->isAdminDinas();
    }
}