<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori_makanans extends Model
{
    use HasFactory;

    protected $table = 'kategori_menus';

    protected $fillable = ['nama_kategori'];
    public $timestamps = true;
    public function menus()
    {
        return $this->hasMany(menus::class, 'kategori_id', 'id');
    }
}
