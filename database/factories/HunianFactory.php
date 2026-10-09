<?php

namespace Database\Factories;

use App\Models\Hunian;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hunian>
 */
class HunianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Hunian::class;
    
    public function definition(): array
    {
        return [
            'id_warga' => Warga::factory(),
            'status_rumah' => fake()->numberBetween(1, 3), // 1=milik sendiri, 2=sewa, 3=menumpang
            'status_tanah' => fake()->numberBetween(1, 3),
            'rumah_lain' => fake()->boolean(10),
            'tanah_lain' => fake()->boolean(10),
            'luas_rumah' => fake()->numberBetween(21, 120),
            'luas_tanah' => fake()->numberBetween(30, 200),
            'kawasan' => fake()->numberBetween(1, 4),
            'jml_kk' => fake()->numberBetween(1, 2),
            'jml_penghuni' => fake()->numberBetween(1, 6),
            // Rentang koordinat area Jawa Timur sebagai contoh data lokasi
            'latitude' => fake()->latitude(-8.2, -7.3),
            'longitude' => fake()->longitude(111.5, 112.7),
        ];
    }
}
