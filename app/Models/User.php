<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: user punya banyak pesanan
    public function pesanan()
    {
        return $this->hasMany(pesanans::class, 'user_id');
    }

    // Relasi: user punya banyak log aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(log_aktivitas::class, 'user_id');
    }
}
