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
        Schema::create('realisasi', function (Blueprint $table) {
            $table->id('id_realisasi');
            $table->foreignId('id_bantuan')
                ->constrained('bantuan', 'id_bantuan')
                ->restrictOnDelete();
            $table->string('foto_0')->nullable();
            $table->string('foto_25')->nullable();
            $table->string('foto_50')->nullable();
            $table->string('foto_100')->nullable();
            $table->tinyInteger('status')->default(0)
                ->comment('progres realisasi: 0/25/50/100 persen');
            $table->date('tgl_realisasi')->nullable();
            $table->string('kwitansi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};
