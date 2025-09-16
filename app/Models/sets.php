<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sets extends Model
{
    protected $table = 'sets';
    protected $fillable = [
        'nama_set',
        'deskripsi',
    ];

    public function menus()
    {
        return $this->hasMany(menus::class, 'set_id');
    }
}
