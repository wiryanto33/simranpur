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
        Schema::create('transaksi_suku_cadang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suku_cadang_id')->constrained('suku_cadang')->onDelete('cascade');
            $table->enum('jenis', ['in', 'out']);
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('laporan_perbaikan_id')->nullable()->constrained('laporan_perbaikan')->onDelete('set null');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_suku_cadang');
    }
};
