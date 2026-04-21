<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_suku_cadang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_kerusakan_id')->constrained('laporan_kerusakan')->onDelete('cascade');
            $table->foreignId('mekanik_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checker_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            
            $table->dateTime('tanggal_permintaan')->useCurrent();
            $table->dateTime('tanggal_approval')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_suku_cadang');
    }
};
