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
        Schema::create('bantuan', function (Blueprint $table) {
            $table->id('id_bantuan');
            $table->foreignId('id_warga')
                ->constrained('warga', 'id_warga')
                ->restrictOnDelete();
            $table->foreignId('id_program')
                ->constrained('program_bantuan', 'id_program')
                ->restrictOnDelete();
            $table->year('tahun_bantuan');
            $table->foreignId('id_user_pengajuan')
                ->constrained('users', 'id')
                ->restrictOnDelete();
            $table->date('tgl_pengajuan');
            $table->tinyInteger('status_bantuan')->default(0)
                ->comment('0=diajukan,1=diverifikasi,2=disetujui,3=direvisi,4=ditolak,5=direalisasikan');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('id_verifikator_bantuan')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
            $table->boolean('is_aktif')->default(true)
                ->comment('true jika ini record terkini dalam rantai pengajuan warga+program+tahun ini');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuans');
    }
};
