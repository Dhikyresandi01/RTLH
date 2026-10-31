<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahKotaKediri = [
            // --- KECAMATAN MOJOROTO (14 Kelurahan) ---
            ['kode_wilayah' => '3571011001', 'desa' => 'Bandar Kidul', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64118'],
            ['kode_wilayah' => '3571011002', 'desa' => 'Bandar Lor', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64117'],
            ['kode_wilayah' => '3571011003', 'desa' => 'Banjarmlati', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64119'],
            ['kode_wilayah' => '3571011004', 'desa' => 'Bujel', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64115'],
            ['kode_wilayah' => '3571011005', 'desa' => 'Campurejo', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64116'],
            ['kode_wilayah' => '3571011006', 'desa' => 'Dermo', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64111'],
            ['kode_wilayah' => '3571011007', 'desa' => 'Gayam', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64113'],
            ['kode_wilayah' => '3571011008', 'desa' => 'Lirboyo', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64117'],
            ['kode_wilayah' => '3571011009', 'desa' => 'Mojoroto', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64112'],
            ['kode_wilayah' => '3571011010', 'desa' => 'Mrican', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64111'],
            ['kode_wilayah' => '3571011011', 'desa' => 'Ngampel', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64112'],
            ['kode_wilayah' => '3571011012', 'desa' => 'Pojok', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64115'],
            ['kode_wilayah' => '3571011013', 'desa' => 'Sukorame', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64114'],
            ['kode_wilayah' => '3571011014', 'desa' => 'Tamanan', 'kecamatan' => 'Mojoroto', 'kode_pos' => '64117'],

            // --- KECAMATAN KOTA (17 Kelurahan) ---
            ['kode_wilayah' => '3571021001', 'desa' => 'Balowerti', 'kecamatan' => 'Kota', 'kode_pos' => '64129'],
            ['kode_wilayah' => '3571021002', 'desa' => 'Banjaran', 'kecamatan' => 'Kota', 'kode_pos' => '64124'],
            ['kode_wilayah' => '3571021003', 'desa' => 'Dandangan', 'kecamatan' => 'Kota', 'kode_pos' => '64122'],
            ['kode_wilayah' => '3571021004', 'desa' => 'Jagalan', 'kecamatan' => 'Kota', 'kode_pos' => '64125'],
            ['kode_wilayah' => '3571021005', 'desa' => 'Kaliombo', 'kecamatan' => 'Kota', 'kode_pos' => '64126'],
            ['kode_wilayah' => '3571021006', 'desa' => 'Kampungdalem', 'kecamatan' => 'Kota', 'kode_pos' => '64126'],
            ['kode_wilayah' => '3571021007', 'desa' => 'Kemasan', 'kecamatan' => 'Kota', 'kode_pos' => '64125'],
            ['kode_wilayah' => '3571021008', 'desa' => 'Manisrenggo', 'kecamatan' => 'Kota', 'kode_pos' => '64128'],
            ['kode_wilayah' => '3571021009', 'desa' => 'Ngadirejo', 'kecamatan' => 'Kota', 'kode_pos' => '64122'],
            ['kode_wilayah' => '3571021010', 'desa' => 'Ngronggo', 'kecamatan' => 'Kota', 'kode_pos' => '64127'],
            ['kode_wilayah' => '3571021011', 'desa' => 'Pakelan', 'kecamatan' => 'Kota', 'kode_pos' => '64123'],
            ['kode_wilayah' => '3571021012', 'desa' => 'Pocanan', 'kecamatan' => 'Kota', 'kode_pos' => '64129'],
            ['kode_wilayah' => '3571021013', 'desa' => 'Rejomulyo', 'kecamatan' => 'Kota', 'kode_pos' => '64129'],
            ['kode_wilayah' => '3571021014', 'desa' => 'Ringinanom', 'kecamatan' => 'Kota', 'kode_pos' => '64126'],
            ['kode_wilayah' => '3571021015', 'desa' => 'Semampir', 'kecamatan' => 'Kota', 'kode_pos' => '64121'],
            ['kode_wilayah' => '3571021016', 'desa' => 'Setonobetek', 'kecamatan' => 'Kota', 'kode_pos' => '64126'],
            ['kode_wilayah' => '3571021017', 'desa' => 'Setonogedong', 'kecamatan' => 'Kota', 'kode_pos' => '64126'],

            // --- KECAMATAN PESANTREN (15 Kelurahan) ---
            ['kode_wilayah' => '3571031001', 'desa' => 'Banaran', 'kecamatan' => 'Pesantren', 'kode_pos' => '64133'],
            ['kode_wilayah' => '3571031002', 'desa' => 'Bangsal', 'kecamatan' => 'Pesantren', 'kode_pos' => '64131'],
            ['kode_wilayah' => '3571031003', 'desa' => 'Bawang', 'kecamatan' => 'Pesantren', 'kode_pos' => '64136'],
            ['kode_wilayah' => '3571031004', 'desa' => 'Betet', 'kecamatan' => 'Pesantren', 'kode_pos' => '64134'],
            ['kode_wilayah' => '3571031005', 'desa' => 'Blabak', 'kecamatan' => 'Pesantren', 'kode_pos' => '64135'],
            ['kode_wilayah' => '3571031006', 'desa' => 'Burengan', 'kecamatan' => 'Pesantren', 'kode_pos' => '64131'],
            ['kode_wilayah' => '3571031007', 'desa' => 'Jamsaren', 'kecamatan' => 'Pesantren', 'kode_pos' => '64132'],
            ['kode_wilayah' => '3571031008', 'desa' => 'Ketami', 'kecamatan' => 'Pesantren', 'kode_pos' => '64139'],
            ['kode_wilayah' => '3571031009', 'desa' => 'Ngletih', 'kecamatan' => 'Pesantren', 'kode_pos' => '64137'],
            ['kode_wilayah' => '3571031010', 'desa' => 'Pakunden', 'kecamatan' => 'Pesantren', 'kode_pos' => '64132'],
            ['kode_wilayah' => '3571031011', 'desa' => 'Pesantren', 'kecamatan' => 'Pesantren', 'kode_pos' => '64131'],
            ['kode_wilayah' => '3571031012', 'desa' => 'Singonegaran', 'kecamatan' => 'Pesantren', 'kode_pos' => '64132'],
            ['kode_wilayah' => '3571031013', 'desa' => 'Tempurejo', 'kecamatan' => 'Pesantren', 'kode_pos' => '64138'],
            ['kode_wilayah' => '3571031014', 'desa' => 'Tinalan', 'kecamatan' => 'Pesantren', 'kode_pos' => '64132'],
            ['kode_wilayah' => '3571031015', 'desa' => 'Tosaren', 'kecamatan' => 'Pesantren', 'kode_pos' => '64133'],
        ];

        foreach ($wilayahKotaKediri as $wilayah) {
            Wilayah::updateOrCreate(
                ['kode_wilayah' => $wilayah['kode_wilayah']],
                $wilayah
            );
        }
    }
}
