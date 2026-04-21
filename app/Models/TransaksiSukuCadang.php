<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSukuCadang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_suku_cadang';
    
    protected $guarded = ['id'];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporanPerbaikan()
    {
        return $this->belongsTo(LaporanPerbaikan::class, 'laporan_perbaikan_id');
    }

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}
