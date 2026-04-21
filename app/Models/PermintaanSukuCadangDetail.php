<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanSukuCadangDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_suku_cadang_detail';
    protected $guarded = ['id'];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanSukuCadang::class, 'permintaan_id');
    }

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'suku_cadang_id');
    }
}
