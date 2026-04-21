<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanSukuCadang extends Model
{
    use HasFactory;

    protected $table = 'permintaan_suku_cadang';
    protected $guarded = ['id'];

    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'laporan_kerusakan_id');
    }

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanSukuCadangDetail::class, 'permintaan_id');
    }

    protected function casts(): array
    {
        return [
            'tanggal_permintaan' => 'datetime',
            'tanggal_approval' => 'datetime',
        ];
    }
}
