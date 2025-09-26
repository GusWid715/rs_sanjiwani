<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketMakanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel.
    protected $table = 'paket_makanan';

    // Kolom yang boleh diisi.
    protected $fillable = ['nama_paket', 'deskripsi'];

    // Relasi many-to-many: Satu paket makanan memiliki banyak menu.
    public function menu()
    {
        return $this->belongsToMany(Menu::class, 'paket_makanan_menu', 'paket_makanan_id', 'menu_id');
    }
}