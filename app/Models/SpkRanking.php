<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkRanking extends Model
{
    use HasFactory;

    protected $table = 'spk_ranking';
    protected $primaryKey = 'id_ranking';

    protected $fillable = [
        'id_bantuan',
        'tahun_anggaran',
        'skor_akhir',
        'ranking',
        'rincian_kriteria',
    ];

    protected function casts(): array
    {
        return [
            'skor_akhir' => 'float',
            'ranking' => 'integer',
            'rincian_kriteria' => 'array',
        ];
    }

    public function bantuan(): BelongsTo
    {
        return $this->belongsTo(Bantuan::class, 'id_bantuan');
    }
}
