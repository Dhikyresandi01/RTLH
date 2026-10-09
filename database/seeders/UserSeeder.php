<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Akun tetap untuk login testing (password sama: "password")
        User::factory()->superadmin()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bansos.test',
            'password' => Hash::make('password'),
        ]);
 
        User::factory()->adminDinas()->create([
            'name' => 'Admin Dinas Satu',
            'email' => 'dinas1@bansos.test',
            'password' => Hash::make('password'),
        ]);
 
        User::factory()->adminDinas()->create([
            'name' => 'Admin Dinas Dua',
            'email' => 'dinas2@bansos.test',
            'password' => Hash::make('password'),
        ]);
 
        // Satu admin kelurahan tetap per wilayah yang sudah ada, untuk testing
        Wilayah::all()->each(function (Wilayah $wilayah, int $index) {
            User::factory()->adminKelurahan()->create([
                'name' => "Admin {$wilayah->desa}",
                'email' => "kelurahan{$index}@bansos.test",
                'password' => Hash::make('password'),
                'id_wilayah' => $wilayah->id_wilayah,
            ]);
        });
    }
}
