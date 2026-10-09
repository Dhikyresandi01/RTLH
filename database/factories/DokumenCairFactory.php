<?php

namespace Database\Factories;

use App\Models\Bantuan;
use App\Models\DokumenCair;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenCair>
 */
class DokumenCairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DokumenCair::class;
    
    public function definition(): array
    {
        return [
            'id_bantuan' => Bantuan::factory(),
            'jenis_foto' => fake()->randomElement(['bukti_transfer', 'foto_penyerahan_bantuan']),
            'path_foto' => 'dokumen/bantuan/cair/' . fake()->uuid() . '.jpg',
        ];
    }
}
