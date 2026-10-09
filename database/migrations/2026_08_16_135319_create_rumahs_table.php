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
        Schema::create('rumah', function (Blueprint $table) {
            $table->id('id_rumah');
            $table->foreignId('id_hunian')
                ->constrained('hunian', 'id_hunian')
                ->cascadeOnDelete();
            $table->tinyInteger('pondasi')->nullable();
            $table->tinyInteger('kolom')->nullable();
            $table->tinyInteger('balok')->nullable();
            $table->tinyInteger('sloof')->nullable();
            $table->tinyInteger('plafon')->nullable();
            $table->tinyInteger('kerangka_atap')->nullable();
            $table->tinyInteger('kondisi_lantai')->nullable();
            $table->tinyInteger('bahan_lantai')->nullable();
            $table->tinyInteger('kondisi_dinding')->nullable();
            $table->tinyInteger('bahan_dinding')->nullable();
            $table->tinyInteger('kondisi_atap')->nullable();
            $table->tinyInteger('bahan_atap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumahs');
    }
};
