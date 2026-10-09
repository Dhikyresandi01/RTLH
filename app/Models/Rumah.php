<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rumah extends Model
{
    //
    use HasFactory;
    
    protected $table = 'rumah';
    protected $primaryKey = 'id_rumah';

    protected $fillable = [
        'id_hunian',
        'pondasi', 'kolom', 'balok', 'sloof', 'plafon', 'kerangka_atap',
        'kondisi_lantai', 'bahan_lantai',
        'kondisi_dinding', 'bahan_dinding',
        'kondisi_atap', 'bahan_atap',
    ];

    public function hunian() : BelongsTo {
        return $this->belongsTo(Hunian::class, 'id_hunian');
    }

    public function Utilitas() : HasMany {
        return $this->hasMany(Utilitas::class, 'id_rumah');
    }

    public function DokumenRumah() : HasMany {
        return $this->hasMany(DokumenRumah::class, 'id_rumah');
    }
}
