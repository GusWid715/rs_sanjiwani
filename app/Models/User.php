<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
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

    public function pesanans()
    {
        return $this->hasMany(pesanans::class);
    }

    public function logs()
    {
        return $this->hasMany(log_aktivitas::class);
    }
}
