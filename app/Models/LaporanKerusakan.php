<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';
    
    protected $guarded = ['id'];

    use \Spatie\Activitylog\Models\Concerns\LogsActivity;

    public function getActivitylogOptions(): \Spatie\Activitylog\Support\LogOptions
    {
        return \Spatie\Activitylog\Support\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function laporanPerbaikan()
    {
        return $this->hasOne(LaporanPerbaikan::class, 'laporan_kerusakan_id');
    }

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'foto' => 'array', // Will store multiple paths via JSON
        ];
    }
}
