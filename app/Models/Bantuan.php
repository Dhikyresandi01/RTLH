<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bantuan extends Model
{
    //
    use HasFactory;
    
    protected $table = 'bantuan';
    protected $primaryKey = 'id_bantuan';

    protected $fillable = [
        'id_warga', 'id_program',
        'tahun_bantuan', 'id_user_pengajuan',
        'tgl_pengajuan', 'status_bantuan',
        'catatan_verifikasi', 'id_verifikator_bantuan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pengajuan' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    // Kode status_bantuan, dipakai bersama enum sederhana di sini
    public const STATUS_DIAJUKAN = 0;
    public const STATUS_DIVERIFIKASI = 1;
    public const STATUS_DISETUJUI = 2;
    public const STATUS_REVISI = 3;
    public const STATUS_DITOLAK = 4;
    public const STATUS_DIREALISASIKAN = 5;

    public function warga() : BelongsTo {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function program() : BelongsTo {
        return $this->belongsTo(ProgramBantuan::class, 'id_program');
    }

    public function userPengajuan() : BelongsTo {
        return $this->belongsTo(User::class, 'id_user_pengajuan');
    }

    public function verifikator() : BelongsTo {
        return $this->belongsTo(User::class, 'id_verifikator_bantuan');
    }

    public function DokumenAwal() : HasMany {
        return $this->hasMany(DokumenAwal::class, 'id_bantuan');
    }

    public function DokumenCair() : HasMany {
        return $this->hasMany(DokumenCair::class, 'id_bantuan');
    }

    public function Realisasi() : HasMany {
        return $this->hasMany(Realisasi::class, 'id_bantuan');
    }

    // Hanya record yang berlaku saat ini dalam rantai pengajuan (bukan riwayat lama)
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Batasi query hanya ke usulan bantuan milik warga di wilayah user.
     * Superadmin & admin dinas melihat semua wilayah.
     *
     * Contoh: Bantuan::visibleTo($request->user())->aktif()->get();
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return $query;
        }
 
        return $query->whereHas('warga', function (Builder $q) use ($user) {
            $q->where('id_wilayah', $user->id_wilayah);
        });
    }
}
