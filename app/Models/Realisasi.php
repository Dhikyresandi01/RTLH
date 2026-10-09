<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Realisasi extends Model
{
    //
    use HasFactory;
    
    protected $table = 'realisasi';
    protected $primaryKey = 'id_realisasi';

    protected $fillable = [
        'id_bantuan',
        'foto_0', 'foto_25', 'foto_50', 'foto_100',
        'status', 'tgl_realisasi', 'kwitansi', 'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tgl_realisasi' => 'date',
        ];
    }

    public function bantuan() : BelongsTo {
        return $this->belongsTo(Bantuan::class, 'id_bantuan');
    }

    public function DokumenRealisasi() : HasMany {
        return $this->hasMany(DokumenRealisasi::class);
    }
}
