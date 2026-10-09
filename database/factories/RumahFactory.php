<?php

namespace Database\Factories;

use App\Models\Hunian;
use App\Models\Rumah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rumah>
 */
class RumahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Rumah::class;
 
    public function definition(): array
    {
        // Kondisi 1=baik, 2=sedang, 3=rusak (dipakai konsisten di semua kolom kondisi)
        return [
            'id_hunian' => Hunian::factory(),
            'pondasi' => fake()->numberBetween(1, 3),
            'kolom' => fake()->numberBetween(1, 3),
            'balok' => fake()->numberBetween(1, 3),
            'sloof' => fake()->numberBetween(1, 3),
            'plafon' => fake()->numberBetween(1, 3),
            'kerangka_atap' => fake()->numberBetween(1, 3),
            'kondisi_lantai' => fake()->numberBetween(1, 3),
            'bahan_lantai' => fake()->numberBetween(1, 3), // 1=keramik, 2=semen, 3=tanah
            'kondisi_dinding' => fake()->numberBetween(1, 3),
            'bahan_dinding' => fake()->numberBetween(1, 3), // 1=tembok, 2=kayu, 3=bambu
            'kondisi_atap' => fake()->numberBetween(1, 3),
            'bahan_atap' => fake()->numberBetween(1, 3), // 1=genteng, 2=seng, 3=asbes
        ];
    }
}
