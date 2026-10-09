<?php

namespace Database\Factories;

use App\Models\ProgramBantuan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramBantuan>
 */
class ProgramBantuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProgramBantuan::class;

    public function definition(): array
    {
        return [
            'nama_program' => fake()->randomElement([
                'RTLH APBD',
                'RTLH',
                'BSPS Provinsi',
                'BSPS APBN',
            ]),
            'sumber_dana' => fake()->randomElement(['APBD', 'APBN', 'CSR Perusahaan']),
            'nominal_bantuan' => fake()->randomElement([10000000, 20000000, 25000000, 50000000, 80000000]),
        ];
    }
}
