<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DokumenRumah;
use App\Models\DokumenWarga;
use App\Models\Hunian;
use App\Models\Rumah;
use App\Models\User;
use App\Models\Utilitas;
use App\Models\Warga;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminKelurahans = User::where('role', 'admin_kelurahan')->get();
 
        foreach ($adminKelurahans as $admin) {
            Warga::factory()
                ->count(8)
                ->create([
                    'id_wilayah' => $admin->id_wilayah,
                    'id_user_input' => $admin->id,
                ])
                ->each(function (Warga $warga) {
                    DokumenWarga::factory()->create(['id_warga' => $warga->id_warga]);
 
                    $hunian = Hunian::factory()->create(['id_warga' => $warga->id_warga]);
                    $rumah = Rumah::factory()->create(['id_hunian' => $hunian->id_hunian]);
                    Utilitas::factory()->create(['id_rumah' => $rumah->id_rumah]);
 
                    DokumenRumah::factory()
                        ->count(3)
                        ->create(['id_rumah' => $rumah->id_rumah]);
                });
        }
    }
}
