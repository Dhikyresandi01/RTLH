<?php

namespace Database\Factories;

use App\Models\DokumenRumah;
use App\Models\Rumah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenRumah>
 */
class DokumenRumahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DokumenRumah::class;
    
    public function definition(): array
    {
        return [
            'id_rumah' => Rumah::factory(),
            'jenis_foto' => fake()->randomElement([
                'foto_rumah_depan',
                'foto_rumah_samping',
                'foto_atap',
                'foto_dinding',
                'foto_lantai',
                'foto_mck',
            ]),
            'path_foto' => 'dokumen/rumah/' . fake()->uuid() . '.jpg',
        ];
    }
}
