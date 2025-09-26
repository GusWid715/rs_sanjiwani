<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use Notifiable, HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'password',
        'role',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function pesanan()
    {
        return $this->hasMany(pesanan::class);
    }

    public function logs()
    {
        return $this->hasMany(log_aktivitas::class);
    }
}
