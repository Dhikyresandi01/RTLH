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
        Schema::create('dokumen_realisasi', function (Blueprint $table) {
            $table->id('id_dokumen_realisasi');
            $table->foreignId('id_realisasi')
                ->constrained('realisasi', 'id_realisasi')
                ->cascadeOnDelete();
            $table->string('jenis_foto', 50);
            $table->string('path_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_realisasis');
    }
};
