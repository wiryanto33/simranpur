<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function detail()
    {
        return $this->hasOne(DetailUser::class);
    }

    public function laporanPerbaikan()
    {
        return $this->hasMany(LaporanPerbaikan::class, 'mekanik_id');
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'pelapor_id');
    }

    /**
     * Ranpur yang ditugaskan untuk operator ini.
     * Shortcut ke detail->kendaraan.
     */
    public function kendaraanTugas()
    {
        return $this->hasOneThrough(
            Kendaraan::class,
            DetailUser::class,
            'user_id',       // FK di detail_user
            'id',            // PK di kendaraan
            'id',            // PK di users
            'kendaraan_id'   // FK di detail_user
        );
    }

    /**
     * Cek apakah user adalah Operator.
     */
    public function isOperator(): bool
    {
        return $this->hasRole('Operator');
    }

    /**
     * Ambil kendaraan_id yang ditugaskan (shortcut praktis).
     */
    public function getKendaraanTugasIdAttribute(): ?int
    {
        return $this->detail?->kendaraan_id;
    }
}
