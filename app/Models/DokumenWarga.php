<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenWarga extends Model
{
    //
    use HasFactory;
    
    protected $table = 'dokumen_warga';
    protected $primaryKey = 'id_dokumen_warga';

    protected $fillable = [
        'id_warga',
        'foto_ktp',
        'foto_kk',
        'foto_dtsen',
    ];

    public function warga() : BelongsTo {
        return $this->belongsTo(Warga::class, 'id_warga');
    }
}
