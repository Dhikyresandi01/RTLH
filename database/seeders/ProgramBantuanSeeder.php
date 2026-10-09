<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramBantuan;

class ProgramBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $programs = [
            ['nama_program' => 'RTLH APBD', 'sumber_dana' => 'APBD', 'nominal_bantuan' => 25000000],
            ['nama_program' => 'BSPS APBN', 'sumber_dana' => 'APBN', 'nominal_bantuan' => 20000000],
            ['nama_program' => 'RTLH', 'sumber_dana' => 'APBD', 'nominal_bantuan' => 10000000],
        ];
 
        foreach ($programs as $program) {
            ProgramBantuan::create($program);
        }
    }
}
