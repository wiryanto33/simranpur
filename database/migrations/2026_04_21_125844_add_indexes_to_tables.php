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
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->index('tanggal');
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('transaksi_suku_cadang', function (Blueprint $table) {
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('transaksi_suku_cadang', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
        });
    }
};
