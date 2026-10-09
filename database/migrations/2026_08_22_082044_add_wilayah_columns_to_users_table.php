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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->enum('role', ['superadmin', 'admin_dinas', 'admin_kelurahan'])->after('password');
            $table->string('jabatan', 100)->after('role')->nullable();
            $table->foreignId('id_wilayah')->after('jabatan')
                ->nullable()
                ->constrained('wilayah', 'id_wilayah')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn([
                'role',
                'jabatan',
                'id_wilayah',
            ]);
        });
    }
};
