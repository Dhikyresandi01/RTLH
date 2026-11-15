<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenRealisasi extends Model
{
    //
    use HasFactory;
    
    protected $table = 'dokumen_realisasi';
    protected $primaryKey = 'id_dokumen_realisasi';

    protected $fillable = [
        'id_realisasi',
        'jenis_foto',
        'path_foto',
    ];

    public function realisasi() : BelongsTo {
        return $this->belongsTo(Realisasi::class, 'id_realisasi');
    }
}
