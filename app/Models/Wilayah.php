<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    //
    use HasFactory;
    
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';

    protected $fillable = [
        'kode_wilayah',
        'desa',
        'kecamatan',
        'kode_pos',
    ];

    public function WilayahUser() : HasMany {
        return $this->hasMany(User::class, 'id_wilayah', 'id_wilayah');
    }

    public function WilayahWarga() : HasMany {
        return $this->hasMany(Warga::class, 'id_wilayah', 'id_wilayah');
    }
}
