<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warga>
 */
class WargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Warga::class;

    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('################'),
            'no_kk' => fake()->numerify('################'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement([1, 2]), // 1=laki-laki, 2=perempuan
            'tempat_lahir' => fake()->city(),
            'tgl_lahir' => fake()->dateTimeBetween('-70 years', '-20 years'),
            'alamat' => fake()->streetAddress(),
            'rt' => fake()->numerify('0#'),
            'rw' => fake()->numerify('0#'),
            'id_wilayah' => Wilayah::factory(),
            'no_hp' => fake()->numerify('08##########'),
            'kawin' => fake()->randomElement([1, 3]), // 1=Belum Kawin, 2=Kawin, 3=Cerai
            'pendidikan' => fake()->numberBetween(1, 6),
            'pekerjaan' => fake()->numberBetween(1, 8),
            'no_npwp' => null,
            'penghasilan' => fake()->numberBetween(1, 4),
            'tabungan' => fake()->numberBetween(1, 3),
            'mampu_swadaya' => fake()->boolean(30),
            'dtsen' => fake()->numberBetween(1, 10),
            'skor_kelayakan' => fake()->numberBetween(1, 5),
            'status_update' => 0,
            'id_user_input' => User::factory()->adminKelurahan(),
            'id_verifikator_warga' => null,
        ];
    }

    public function terverifikasi(): static
    {
        return $this->state(fn () => [
            'status_update' => 1,
        ])->afterMaking(function (Warga $warga) {
            $warga->id_verifikator_warga ??= User::factory()->adminDinas()->create()->id_user;
        });
    }
}
