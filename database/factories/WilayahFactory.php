<?php

namespace Database\Factories;

use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wilayah>
 */
class WilayahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Wilayah::class;

    public function definition(): array
    {
        return [
            'kode_wilayah' => fake()->unique()->numerify('######'),
            'desa' => 'Kelurahan ' . fake()->unique()->citySuffix() . ' ' . fake()->lastName(),
            'kecamatan' => 'Kecamatan ' . fake()->city(),
            'kode_pos' => fake()->numerify('#####'),
        ];
    }
}
