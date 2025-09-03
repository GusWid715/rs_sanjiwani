<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Detail_pesanans extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanans';

    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'jumlah',
    ];

    public function pesanan()
    {
        return $this->belongsTo(pesanans::class, 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(menus::class, 'menu_id');
    }
}
