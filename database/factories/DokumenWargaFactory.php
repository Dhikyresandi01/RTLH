<?php

namespace Database\Factories;

use App\Models\DokumenWarga;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenWarga>
 */
class DokumenWargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DokumenWarga::class;
    
    public function definition(): array
    {
        return [
            'id_warga' => Warga::factory(),
            'foto_ktp' => 'dokumen/ktp/' . fake()->uuid() . '.jpg',
            'foto_kk' => 'dokumen/kk/' . fake()->uuid() . '.jpg',
            'foto_dtsen' => 'dokumen/dtsen/' . fake()->uuid() . '.jpg',
        ];
    }
}
