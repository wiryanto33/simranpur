<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_pemeliharaan_mekanik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pemeliharaan_id')->constrained('jadwal_pemeliharaan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        $jadwals = DB::table('jadwal_pemeliharaan')->get();
        foreach ($jadwals as $jadwal) {
            DB::table('jadwal_pemeliharaan_mekanik')->insert([
                'jadwal_pemeliharaan_id' => $jadwal->id,
                'user_id' => $jadwal->mekanik_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->dropForeign(['mekanik_id']);
            $table->dropColumn('mekanik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->foreignId('mekanik_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        $pivots = DB::table('jadwal_pemeliharaan_mekanik')->get();
        foreach ($pivots as $pivot) {
            DB::table('jadwal_pemeliharaan')->where('id', $pivot->jadwal_pemeliharaan_id)->update([
                'mekanik_id' => $pivot->user_id
            ]);
        }

        Schema::dropIfExists('jadwal_pemeliharaan_mekanik');
    }
};
