<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bantuan;
use App\Models\DokumenAwal;
use App\Models\DokumenCair;
use App\Models\DokumenRealisasi;
use App\Models\ProgramBantuan;
use App\Models\Realisasi;
use App\Models\User;
use App\Models\Warga;

class BantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $programs = ProgramBantuan::all();
        $adminDinas = User::where('role', 'admin_dinas')->get();
        $wargas = Warga::all();
 
        foreach ($wargas as $index => $warga) {
            $program = $programs->random();
            $pengaju = User::where('id_wilayah', $warga->id_wilayah)
                ->where('role', 'admin_kelurahan')
                ->first();
 
            // Variasikan status supaya data lebih realistis untuk testing dashboard/laporan
            $status = match (true) {
                $index % 6 === 0 => 'diajukan',
                $index % 6 === 1 => 'diverifikasi',
                $index % 6 === 2 => 'disetujui',
                $index % 6 === 3 => 'direvisi',
                $index % 6 === 4 => 'direalisasikan',
                default => 'ditolak',
            };
 
            $factory = Bantuan::factory();
            if ($status !== 'diajukan') {
                $factory = $factory->{$status}();
            }
 
            $bantuan = $factory->create([
                'id_warga' => $warga->id_warga,
                'id_program' => $program->id_program,
                'id_user_pengajuan' => $pengaju?->id,
            ]);
 
            DokumenAwal::factory()->count(2)->create(['id_bantuan' => $bantuan->id_bantuan]);
 
            if ($status === 'direalisasikan') {
                DokumenCair::factory()->count(2)->create(['id_bantuan' => $bantuan->id_bantuan]);
 
                $realisasi = Realisasi::factory()->create(['id_bantuan' => $bantuan->id_bantuan]);
                DokumenRealisasi::factory()->count(2)->create(['id_realisasi' => $realisasi->id_realisasi]);
            }
        }
    }
}
