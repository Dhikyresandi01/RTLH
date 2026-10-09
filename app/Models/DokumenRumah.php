<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenRumah extends Model
{
    //
    use HasFactory;
    
    protected $table = 'dokumen_rumah';
    protected $primaryKey = 'id_dokumen_rumah';

    protected $fillable = [
        'id_rumah',
        'jenis_foto',
        'path_foto',
    ];

    public function rumah() : BelongsTo {
        return $this->belongsTo(Rumah::class, 'id_rumah');
    }
}
