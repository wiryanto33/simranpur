<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kendaraan extends Model
{
    use SoftDeletes, \Spatie\Activitylog\Models\Concerns\LogsActivity;
    
    public function getActivitylogOptions(): \Spatie\Activitylog\Support\LogOptions
    {
        return \Spatie\Activitylog\Support\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
    
    protected $table = 'kendaraan';
    protected $guarded = ['id'];

    // Relasi Kompi
    public function kompi()
    {
        return $this->belongsTo(Kompi::class);
    }

    // Relasi Jadwal Pemeliharaan
    public function jadwalPemeliharaan()
    {
        return $this->hasMany(JadwalPemeliharaan::class);
    }

    // Relasi Laporan Kerusakan
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class);
    }

    // Accessor untuk badge warna status
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Siap Tempur' => 'green',
            'Standby' => 'blue',
            'Perbaikan' => 'yellow',
            'Tidak Layak' => 'red',
            default => 'gray',
        };
    }
}
