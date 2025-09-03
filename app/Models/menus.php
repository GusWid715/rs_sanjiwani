<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class menus extends Model
{
    use HasFactory;

    protected $table = 'menus'; // sesuai SQL dump (bukan 'menus')

    protected $fillable = [
        'nama_menu',
        'kategori_id',
        'deskripsi',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori_makanans::class, 'kategori_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(detail_pesanans::class, 'menu_id');
    }
}
