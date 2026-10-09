<?php

namespace Database\Factories;

use App\Models\Bantuan;
use App\Models\Realisasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Realisasi>
 */
class RealisasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Realisasi::class;

    public function definition(): array
    {
        return [
            'id_bantuan' => Bantuan::factory()->direalisasikan(),
            'foto_0' => 'dokumen/realisasi/progres_0/' . fake()->uuid() . '.jpg',
            'foto_25' => 'dokumen/realisasi/progres_25/' . fake()->uuid() . '.jpg',
            'foto_50' => 'dokumen/realisasi/progres_50/' . fake()->uuid() . '.jpg',
            'foto_100' => 'dokumen/realisasi/progres_100/' . fake()->uuid() . '.jpg',
            'status' => 100,
            'tgl_realisasi' => fake()->dateTimeBetween('-3 months', 'now'),
            'kwitansi' => 'dokumen/realisasi/kwitansi/' . fake()->uuid() . '.pdf',
            'deskripsi' => 'Bantuan telah disalurkan dan diterima oleh penerima manfaat.',
        ];
    }
}
