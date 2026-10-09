<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->id('id_warga');
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16);
            $table->string('nama', 100);
            $table->tinyInteger('jenis_kelamin')->comment('1=laki-laki, 2=perempuan');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('alamat', 150)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->foreignId('id_wilayah')
                ->constrained('wilayah', 'id_wilayah')
                ->restrictOnDelete();
            $table->string('no_hp', 15)->nullable();
            $table->tinyInteger('kawin')->comment('1=Belum Kawin, 2=Kawin, 3=Cerai');
            $table->tinyInteger('pendidikan')->nullable();
            $table->tinyInteger('pekerjaan')->nullable();
            $table->string('no_npwp', 20)->nullable();
            $table->tinyInteger('penghasilan')->nullable();
            $table->tinyInteger('tabungan')->nullable();
            $table->boolean('mampu_swadaya')->default(false);
            $table->tinyInteger('dtsen')->nullable();
            $table->tinyInteger('skor_kelayakan')->nullable();
            $table->tinyInteger('status_update')->default(0)
                ->comment('status verifikasi profil warga, bukan status usulan bantuan');
            $table->foreignId('id_user_input')
                ->constrained('users', 'id')
                ->restrictOnDelete();
            $table->foreignId('id_verifikator_warga')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};
