<?php

namespace Database\Factories;

use App\Models\Rumah;
use App\Models\Utilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Utilitas>
 */
class UtilitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Utilitas::class;

    public function definition(): array
    {
        return [
            'id_rumah' => Rumah::factory(),
            'jendela' => fake()->numberBetween(1, 3), // 1=tidak ada; 2=ada, kurang mencukupi; 3=ada, mencukupi 
            'ventilasi' => fake()->numberBetween(1, 3), // 1=tidak ada; 2=ada, kurang mencukupi; 3=ada, mencukupi
            'sumber_air' => fake()->numberBetween(1, 3), // 1=PDAM, 2=sumur, 3=sungai
            'mck' => fake()->numberBetween(1, 3), // 1=ada, 2=ada diluar, 3=tidak ada
            'jenis_jamban' => fake()->numberBetween(1, 3), // 1=leher angsa, 2=plung, 3=tidak ada
            'jenis_tpa' => fake()->numberBetween(1, 3), // tempat pembuangan akhir tinja
            'jarak_septik_tank' => fake()->numberBetween(1, 20),
            'sumber_penerangan' => fake()->numberBetween(1, 2), // 1=listrik PLN, 2=non-PLN
            'daya_listrik' => fake()->randomElement([450, 900, 1300, 2200]),
            'bahan_bakar_masak' => fake()->numberBetween(1, 3), // 1=gas, 2=kayu bakar, 3=lainnya
        ];
    }
}
