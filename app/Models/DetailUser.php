<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $table = 'detail_user';

    protected $fillable = [
        'user_id',
        'avatar',
        'jabatan',
        'pangkat',
        'kompi_id',
        'kendaraan_id', // Ranpur yang ditugaskan (untuk role Operator)
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kompi()
    {
        return $this->belongsTo(Kompi::class);
    }

    /**
     * Ranpur yang ditugaskan ke operator ini.
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
