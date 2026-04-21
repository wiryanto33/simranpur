<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompi extends Model
{
    protected $table = 'kompi';
    protected $guarded = ['id'];

    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class);
    }
}
