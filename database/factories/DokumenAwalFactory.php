<?php

namespace Database\Factories;

use App\Models\Bantuan;
use App\Models\DokumenAwal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenAwal>
 */
class DokumenAwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = DokumenAwal::class;
    
    public function definition(): array
    {
        return [
            'id_bantuan' => Bantuan::factory(),
            'jenis_foto' => fake()->randomElement(['surat_pengantar_rt', 'surat_keterangan_tidak_mampu']),
            'path_foto' => 'dokumen/bantuan/awal/' . fake()->uuid() . '.jpg',
        ];
    }
}
