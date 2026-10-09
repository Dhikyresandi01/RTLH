<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hunian extends Model
{
    //
    use HasFactory;
    
    protected $table = 'hunian';
    protected $primaryKey = 'id_hunian';

    protected $fillable = [
        'id_warga',
        'status_rumah', 'status_tanah',
        'rumah_lain', 'tanah_lain',
        'luas_rumah', 'luas_tanah',
        'kawasan', 'jml_kk', 'jml_penghuni',
        'latitude', 'longitude',
    ];

    protected function casts(): array
    {
        return [
            'rumah_lain' => 'boolean',
            'tanah_lain' => 'boolean',
            'kawasan' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
    
    public function warga() : BelongsTo {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function Rumah() : HasMany {
        return $this->hasMany(Rumah::class, 'id_hunian');
    }
}
