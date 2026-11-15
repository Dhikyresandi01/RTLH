<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Warga extends Model
{
    //
    use HasFactory;
    
    protected $table = 'warga';
    protected $primaryKey = 'id_warga';

    protected $fillable = [
        'nik', 'no_kk', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir',
        'alamat', 'rt', 'rw', 'id_wilayah',
        'no_hp', 'kawin', 'pendidikan', 'pekerjaan', 'no_npwp', 'penghasilan', 'tabungan',
        'mampu_swadaya', 'dtsen', 'skor_kelayakan', 'status_update',
        'id_user_input', 'id_verifikator_warga',
    ];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'mampu_swadaya' => 'boolean',
        ];
    }

    public function wilayah() : BelongsTo {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function userInput() : BelongsTo {
        return $this->belongsTo(User::class, 'id_user_input');
    }

    public function userVerifikator() : BelongsTo {
        return $this->belongsTo(User::class, 'id_verifikator_warga');
    }

    public function Dokumen() : HasOne {
        return $this->hasOne(DokumenWarga::class, 'id_warga');
    }

    public function Hunian() : HasMany {
        return $this->hasMany(Hunian::class, 'id_warga');
    }

    public function Bantuan() : HasMany {
        return $this->hasMany(Bantuan::class, 'id_warga');
    }

    public function bantuanAktif() : HasOne {
        return $this->hasOne(Bantuan::class, 'id_warga')->where('is_aktif', true)->latestOfMany('id_bantuan');
    }

    public function spkRanking() : HasOneThrough {
        return $this->hasOneThrough(
            SpkRanking::class,
            Bantuan::class,
            'id_warga',
            'id_bantuan',
            'id_warga',
            'id_bantuan'
        );
    }

    /**
     * Batasi query hanya ke data yang boleh dilihat user.
     * Superadmin & admin dinas melihat semua; admin kelurahan
     * hanya melihat warga di wilayahnya sendiri.
     *
     * Contoh: Warga::visibleTo($request->user())->paginate();
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperadmin() || $user->isAdminDinas()) {
            return $query;
        }
 
        return $query->where('id_wilayah', $user->id_wilayah);
    }
}
