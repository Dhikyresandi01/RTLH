<?php

namespace Database\Seeders;

use App\Models\SpkKriteria;
use Illuminate\Database\Seeder;

class SpkKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriterias = [
            [
                'kode' => 'C1',
                'nama_kriteria' => 'Tingkat Kerusakan Fisik Bangunan',
                'jenis' => 'benefit',
                'bobot' => 0.30,
                'keterangan' => 'Akumulasi tingkat kerusakan atap, dinding, lantai, dan struktur utama rumah (skala semakin rusak semakin prioritas).',
            ],
            [
                'kode' => 'C2',
                'nama_kriteria' => 'Kepadatan / Luas Hunian per Jiwa',
                'jenis' => 'cost',
                'bobot' => 0.20,
                'keterangan' => 'Rasio luas lantai rumah terhadap jumlah anggota keluarga (semakin sempit m² per jiwa semakin mendesak).',
            ],
            [
                'kode' => 'C3',
                'nama_kriteria' => 'Kondisi Sanitasi & Air Minum',
                'jenis' => 'benefit',
                'bobot' => 0.20,
                'keterangan' => 'Kualitas ketersediaan air bersih, fasilitas MCK, dan jamban keluarga (semakin tidak layak semakin prioritas).',
            ],
            [
                'kode' => 'C4',
                'nama_kriteria' => 'Tingkat Kesejahteraan (DTSEN)',
                'jenis' => 'benefit',
                'bobot' => 0.20,
                'keterangan' => 'Prioritas desil DTSEN/DTKS, di mana Desil 1 (Sangat Miskin) memiliki tingkat urgensi bantuan tertinggi.',
            ],
            [
                'kode' => 'C5',
                'nama_kriteria' => 'Tingkat Kemampuan Finansial',
                'jenis' => 'cost',
                'bobot' => 0.10,
                'keterangan' => 'Tingkat penghasilan kepala keluarga (semakin rendah penghasilan semakin diprioritaskan).',
            ],
        ];

        foreach ($kriterias as $item) {
            SpkKriteria::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
