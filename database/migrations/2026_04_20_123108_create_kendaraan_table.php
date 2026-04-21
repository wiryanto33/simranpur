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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_ranpur')->unique();
            $table->string('nama');
            $table->string('jenis');
            $table->integer('tahun');
            $table->string('foto')->nullable();
            $table->string('status')->default('Siap Tempur');
            $table->foreignId('kompi_id')->constrained('kompi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
