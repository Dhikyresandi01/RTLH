<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Utilitas extends Model
{
    //
    use HasFactory;
    
    protected $table = 'utilitas';
    protected $primaryKey = 'id_utilitas';

    protected $fillable = [
        'id_rumah',
        'jendela', 'ventilasi',
        'sumber_air', 'mck', 'jenis_jamban',
        'jenis_tpa', 'jarak_septik_tank',
        'sumber_penerangan', 'daya_listrik',
        'bahan_bakar_masak',
    ];

    public function rumah() : BelongsTo {
        return $this->belongsTo(Rumah::class, 'id_rumah');
    }
}
