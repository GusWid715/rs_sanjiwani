<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pesanans extends Model
{
    protected $table = 'pesanans';
    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'tanggal',
        'status',
        'ruangan',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(menus::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(detail_pesanans::class);
    }
}
