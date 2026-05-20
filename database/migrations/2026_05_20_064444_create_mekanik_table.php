<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mekanik', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('pangkat')->nullable();
            $table->string('nrp')->nullable()->unique();
            $table->string('jabatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mekanik');
    }
};
