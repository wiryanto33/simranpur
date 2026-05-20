<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mekanik extends Model
{
    protected $table = 'mekanik';
    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'string',
    ];

    public function jadwals()
    {
        return $this->belongsToMany(JadwalPemeliharaan::class, 'jadwal_pemeliharaan_mekanik', 'mekanik_id', 'jadwal_pemeliharaan_id');
    }

    public function laporanPerbaikan()
    {
        return $this->hasMany(LaporanPerbaikan::class, 'mekanik_id');
    }

    public function permintaanSukuCadang()
    {
        return $this->hasMany(PermintaanSukuCadang::class, 'mekanik_id');
    }

    /**
     * Helper: tampilkan nama lengkap dengan pangkat
     */
    public function getNamaLengkapAttribute(): string
    {
        return trim(($this->pangkat ? $this->pangkat . ' ' : '') . $this->nama);
    }
}
