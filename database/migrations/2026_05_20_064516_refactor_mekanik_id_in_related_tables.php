<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================================
        // 1. PIVOT TABLE: jadwal_pemeliharaan_mekanik
        //    Sudah dimodifikasi sebelumnya (DDL MySQL auto-commit), skip jika
        //    user_id sudah tidak ada.
        // =====================================================================
        if (Schema::hasColumn('jadwal_pemeliharaan_mekanik', 'user_id')) {
            DB::table('jadwal_pemeliharaan_mekanik')->truncate();
            Schema::table('jadwal_pemeliharaan_mekanik', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
                $table->foreignId('mekanik_id')->constrained('mekanik')->onDelete('cascade');
            });
        }

        // =====================================================================
        // 2. TABEL: laporan_perbaikan
        //    Ubah mekanik_id FK dari users → mekanik (nullable, set null on delete)
        // =====================================================================

        // Hapus FK lama jika masih ada
        try {
            Schema::table('laporan_perbaikan', function (Blueprint $table) {
                $table->dropForeign(['mekanik_id']);
            });
        } catch (\Exception $e) {
            // FK mungkin sudah dihapus sebelumnya
        }

        // Ubah kolom menjadi nullable
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->unsignedBigInteger('mekanik_id')->nullable()->change();
        });

        // Set semua mekanik_id ke NULL (user lama sudah tidak ada)
        DB::table('laporan_perbaikan')->update(['mekanik_id' => null]);

        // Tambah FK baru ke tabel mekanik
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->foreign('mekanik_id')->references('id')->on('mekanik')->onDelete('set null');
        });

        // =====================================================================
        // 3. TABEL: permintaan_suku_cadang
        //    Ubah mekanik_id FK dari users → mekanik (nullable, set null on delete)
        // =====================================================================

        // Hapus FK lama jika masih ada
        try {
            Schema::table('permintaan_suku_cadang', function (Blueprint $table) {
                $table->dropForeign(['mekanik_id']);
            });
        } catch (\Exception $e) {
            // FK mungkin sudah dihapus sebelumnya
        }

        // Ubah kolom menjadi nullable
        Schema::table('permintaan_suku_cadang', function (Blueprint $table) {
            $table->unsignedBigInteger('mekanik_id')->nullable()->change();
        });

        // Set semua mekanik_id ke NULL
        DB::table('permintaan_suku_cadang')->update(['mekanik_id' => null]);

        // Tambah FK baru ke tabel mekanik
        Schema::table('permintaan_suku_cadang', function (Blueprint $table) {
            $table->foreign('mekanik_id')->references('id')->on('mekanik')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Rollback pivot table
        if (Schema::hasColumn('jadwal_pemeliharaan_mekanik', 'mekanik_id')) {
            DB::table('jadwal_pemeliharaan_mekanik')->truncate();
            Schema::table('jadwal_pemeliharaan_mekanik', function (Blueprint $table) {
                $table->dropForeign(['mekanik_id']);
                $table->dropColumn('mekanik_id');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            });
        }

        // Rollback laporan_perbaikan
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->dropForeign(['mekanik_id']);
        });
        Schema::table('laporan_perbaikan', function (Blueprint $table) {
            $table->unsignedBigInteger('mekanik_id')->nullable(false)->change();
            $table->foreign('mekanik_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Rollback permintaan_suku_cadang
        Schema::table('permintaan_suku_cadang', function (Blueprint $table) {
            $table->dropForeign(['mekanik_id']);
        });
        Schema::table('permintaan_suku_cadang', function (Blueprint $table) {
            $table->unsignedBigInteger('mekanik_id')->nullable(false)->change();
            $table->foreign('mekanik_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
