<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkKriteria extends Model
{
    use HasFactory;

    protected $table = 'spk_kriteria';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'kode',
        'nama_kriteria',
        'jenis',
        'bobot',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'float',
        ];
    }
}
