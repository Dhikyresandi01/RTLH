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
        Schema::create('hunian', function (Blueprint $table) {
            $table->id('id_hunian');
            $table->foreignId('id_warga')
                ->constrained('warga', 'id_warga')
                ->cascadeOnDelete();
            $table->tinyInteger('status_rumah')->nullable();
            $table->tinyInteger('status_tanah')->nullable();
            $table->boolean('rumah_lain')->default(false);
            $table->boolean('tanah_lain')->default(false);
            $table->unsignedInteger('luas_rumah')->nullable();
            $table->unsignedInteger('luas_tanah')->nullable();
            $table->tinyInteger('kawasan')->nullable();
            $table->unsignedTinyInteger('jml_kk')->nullable();
            $table->unsignedTinyInteger('jml_penghuni')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hunians');
    }
};
