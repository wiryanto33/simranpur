<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPemeliharaan extends Model
{
    use \Spatie\Activitylog\Models\Concerns\LogsActivity;

    public function getActivitylogOptions(): \Spatie\Activitylog\Support\LogOptions
    {
        return \Spatie\Activitylog\Support\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
    protected $table = 'jadwal_pemeliharaan';
    protected $guarded = ['id'];
    protected $casts = [
        'checklist' => 'array',
        'tanggal' => 'date',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
    
    public function mekanik()
    {
        return $this->belongsToMany(User::class, 'jadwal_pemeliharaan_mekanik', 'jadwal_pemeliharaan_id', 'user_id');
    }
}
