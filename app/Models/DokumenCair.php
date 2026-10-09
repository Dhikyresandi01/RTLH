<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenCair extends Model
{
    //
    use HasFactory;
    
    protected $table = 'dokumen_cair';
    protected $primaryKey = 'id_dokumen_cair';

    protected $fillable = [
        'id_bantuan',
        'jenis_foto',
        'path_foto',
    ];

    public function bantuan() : BelongsTo {
        return $this->belongsTo(Bantuan::class, 'id_bantuan');
    }
}
