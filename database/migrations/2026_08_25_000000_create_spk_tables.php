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
        // 1. Tabel Kriteria & Bobot Dinamis SPK SAW
        Schema::create('spk_kriteria', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('kode', 10)->unique(); // C1, C2, dst.
            $table->string('nama_kriteria', 100);
            $table->enum('jenis', ['benefit', 'cost']);
            $table->decimal('bobot', 5, 2); // Nilai bobot (contoh: 0.30)
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Penyimpanan Riwayat Hasil Perankingan SPK SAW
        Schema::create('spk_ranking', function (Blueprint $table) {
            $table->id('id_ranking');
            $table->foreignId('id_bantuan')
                ->constrained('bantuan', 'id_bantuan')
                ->cascadeOnDelete();
            $table->year('tahun_anggaran');
            $table->decimal('skor_akhir', 8, 4); // Nilai preferensi Vi
            $table->unsignedInteger('ranking');
            $table->json('rincian_kriteria')->nullable(); // Snapshot nilai mentah, r_ij, dan bobot
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_ranking');
        Schema::dropIfExists('spk_kriteria');
    }
};
