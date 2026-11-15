<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_bantuan', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nominal_bantuan')->comment('Keterangan / deskripsi program bantuan');
            $table->enum('jenis_bantuan', ['fisik', 'non_fisik', 'stimulan'])->default('fisik')->after('deskripsi')->comment('Jenis bantuan: fisik, non_fisik, atau stimulan');
            $table->boolean('is_aktif')->default(true)->after('jenis_bantuan')->comment('Status apakah program masih aktif');
        });
    }

    public function down(): void
    {
        Schema::table('program_bantuan', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'jenis_bantuan', 'is_aktif']);
        });
    }
};
