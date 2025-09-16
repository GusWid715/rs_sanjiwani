<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_pesanans extends Model
{
    protected $table = 'detail_pesanans';
    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'jumlah',
    ];

    public function pesanan()
    {
        return $this->belongsTo(pesanans::class);
    }

    public function menu()
    {
        return $this->belongsTo(menus::class);
    }
}
