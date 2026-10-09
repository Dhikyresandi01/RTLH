<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramBantuan extends Model
{
    //
    use HasFactory;
    
    protected $table = 'program_bantuan';
    protected $primaryKey = 'id_program';

    protected $fillable = [
        'nama_program',
        'sumber_dana',
        'nominal_bantuan',
    ];

    protected function casts(): array
    {
        return [
            'nominal_bantuan' => 'decimal:2',
        ];
    }
    public function Bantuan() : HasMany {
        return $this->hasMany(Bantuan::class, 'id_program');
    }
}
