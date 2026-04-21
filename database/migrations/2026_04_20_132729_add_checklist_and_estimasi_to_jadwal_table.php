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
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->json('checklist')->nullable();
            $table->integer('estimasi_hari')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->dropColumn(['checklist', 'estimasi_hari']);
        });
    }
};
