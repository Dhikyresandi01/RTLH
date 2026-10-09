<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jabatan',
        'id_wilayah',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }
 
    public function WargaDiinput(): HasMany
    {
        return $this->hasMany(Warga::class, 'id_user_input', 'id');
    }
 
    public function WargaDiverifikasi(): HasMany
    {
        return $this->hasMany(Warga::class, 'id_verifikator_warga', 'id');
    }
 
    public function BantuanDiajukan(): HasMany
    {
        return $this->hasMany(Bantuan::class, 'id_user_pengajuan', 'id');
    }
 
    public function BantuanDiverifikasi(): HasMany
    {
        return $this->hasMany(Bantuan::class, 'id_verifikator_bantuan', 'id');
    }
 
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }
 
    public function isAdminDinas(): bool
    {
        return $this->role === 'admin_dinas';
    }
 
    public function isAdminKelurahan(): bool
    {
        return $this->role === 'admin_kelurahan';
    }
}
