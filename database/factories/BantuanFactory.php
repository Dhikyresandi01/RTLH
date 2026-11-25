<?php

namespace Database\Factories;

use App\Models\Bantuan;
use App\Models\ProgramBantuan;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bantuan>
 */
class BantuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Bantuan::class;
    
    public function definition(): array
    {
        return [
            'id_warga' => Warga::factory(),
            'id_program' => ProgramBantuan::factory(),
            'tahun_bantuan' => now()->year,
            'id_user_pengajuan' => User::factory()->adminKelurahan(),
            'tgl_pengajuan' => fake()->dateTimeBetween('-6 months', 'now'),
            'status_bantuan' => Bantuan::STATUS_DIAJUKAN,
            'catatan_verifikasi' => null,
            'id_verifikator_bantuan' => null,
            // 'id_pengajuan_induk' => null,
            // 'no_pengajuan_ke' => 1,
            'is_aktif' => true,
        ];
    }
 
    public function diverifikasi(): static
    {
        return $this->state(fn () => [
            'status_bantuan' => Bantuan::STATUS_DIVERIFIKASI,
            'id_verifikator_bantuan' => User::where('role', 'admin_dinas')->inRandomOrder()->value('id') ?? User::factory()->adminDinas(),
        ]);
    }
 
    public function disetujui(): static
    {
        return $this->state(fn () => [
            'status_bantuan' => Bantuan::STATUS_DISETUJUI,
            'id_verifikator_bantuan' => User::where('role', 'admin_dinas')->inRandomOrder()->value('id') ?? User::factory()->adminDinas(),
        ]);
    }
 
    public function direvisi(string $catatan = 'Data dukung kurang lengkap, mohon dilengkapi kembali.'): static
    {
        return $this->state(fn () => [
            'status_bantuan' => Bantuan::STATUS_REVISI,
            'catatan_verifikasi' => $catatan,
            'id_verifikator_bantuan' => User::where('role', 'admin_dinas')->inRandomOrder()->value('id') ?? User::factory()->adminDinas(),
        ]);
    }

    public function ditolak(string $catatan = 'Data tidak sesuai persyaratan.'): static
    {
        return $this->state(fn () => [
            'status_bantuan' => Bantuan::STATUS_DITOLAK,
            'catatan_verifikasi' => $catatan,
            'id_verifikator_bantuan' => User::where('role', 'admin_dinas')->inRandomOrder()->value('id') ?? User::factory()->adminDinas(),
        ]);
    }
 
    public function direalisasikan(): static
    {
        return $this->state(fn () => [
            'status_bantuan' => Bantuan::STATUS_DIREALISASIKAN,
            'id_verifikator_bantuan' => User::factory()->adminDinas(),
        ]);
    }
}
