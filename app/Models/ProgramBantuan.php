<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramBantuan extends Model
{
    use HasFactory;
    
    protected $table = 'program_bantuan';
    protected $primaryKey = 'id_program';

    protected $fillable = [
        'nama_program',
        'sumber_dana',
        'nominal_bantuan',
        'deskripsi',
        'jenis_bantuan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'nominal_bantuan' => 'decimal:2',
            'is_aktif' => 'boolean',
        ];
    }

    public function Bantuan() : HasMany {
        return $this->hasMany(Bantuan::class, 'id_program');
    }

    public function scopeAktif(Builder $query): Builder {
        return $query->where('is_aktif', true);
    }
}
