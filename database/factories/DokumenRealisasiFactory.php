<?php

namespace Database\Factories;

use App\Models\DokumenRealisasi;
use App\Models\Realisasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DokumenRealisasi>
 */
class DokumenRealisasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DokumenRealisasi::class;
    
    public function definition(): array
    {
        return [
            'id_realisasi' => Realisasi::factory(),
            'jenis_foto' => fake()->randomElement(['dokumentasi_serah_terima', 'tanda_tangan_penerima']),
            'path_foto' => 'dokumen/realisasi/lainnya/' . fake()->uuid() . '.jpg',
        ];
    }
}
