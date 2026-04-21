<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom kendaraan_id pada detail_user
     * agar operator dapat ditugaskan ke satu ranpur tertentu.
     */
    public function up(): void
    {
        Schema::table('detail_user', function (Blueprint $table) {
            $table->foreignId('kendaraan_id')
                  ->nullable()
                  ->after('kompi_id')
                  ->constrained('kendaraan')
                  ->onDelete('set null')
                  ->comment('Ranpur yang ditugaskan untuk operator');
        });
    }

    public function down(): void
    {
        Schema::table('detail_user', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
            $table->dropColumn('kendaraan_id');
        });
    }
};
