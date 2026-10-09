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
        Schema::create('utilitas', function (Blueprint $table) {
            $table->id('id_utilitas');
            $table->foreignId('id_rumah')
                ->constrained('rumah', 'id_rumah')
                ->cascadeOnDelete();
            $table->tinyInteger('jendela')->nullable();
            $table->tinyInteger('ventilasi')->nullable();
            $table->tinyInteger('sumber_air')->nullable();
            $table->tinyInteger('mck')->nullable();
            $table->tinyInteger('jenis_jamban')->nullable();
            $table->tinyInteger('jenis_tpa')->nullable();
            $table->unsignedSmallInteger('jarak_septik_tank')->nullable();
            $table->tinyInteger('sumber_penerangan')->nullable();
            $table->unsignedInteger('daya_listrik')->nullable();
            $table->tinyInteger('bahan_bakar_masak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilitas');
    }
};
