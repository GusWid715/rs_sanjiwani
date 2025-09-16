<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menus extends Model
{
    protected $table = 'menus';
    protected $fillable = [
        'set_id',
        'nama_menu',
        'deskripsi',
        'stok',
        'image',
    ];

    public function set()
    {
        return $this->belongsTo(sets::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanans::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(detail_pesanans::class);
    }
}
