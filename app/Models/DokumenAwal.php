<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenAwal extends Model
{
    //
    use HasFactory;
    
    protected $table = 'dokumen_awal';
    protected $primaryKey = 'id_dokumen_awal';

    protected $fillable = [
        'id_bantuan',
        'jenis_foto',
        'path_foto',
    ];

    public function bantuan() : BelongsTo {
        return $this->belongsTo(Bantuan::class, 'id_bantuan');
    }
}
