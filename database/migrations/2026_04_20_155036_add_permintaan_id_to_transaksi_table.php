<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_suku_cadang', function (Blueprint $table) {
            $table->foreignId('permintaan_id')->nullable()->after('laporan_perbaikan_id')->constrained('permintaan_suku_cadang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_suku_cadang', function (Blueprint $table) {
            $table->dropForeign(['permintaan_id']);
            $table->dropColumn('permintaan_id');
        });
    }
};
