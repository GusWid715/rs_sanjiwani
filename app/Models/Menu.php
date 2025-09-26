<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Menentukan nama tabel.
    protected $table = 'menu';

    // Kolom yang boleh diisi.
    protected $fillable = ['nama_menu', 'kategori_id', 'deskripsi', 'stok'];

    // Relasi many-to-one: Satu menu dimiliki oleh satu kategori.
    public function kategoriMenu()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }

    // Relasi many-to-many: Satu menu bisa ada di banyak paket makanan.
    public function paketMakanan()
    {
        return $this->belongsToMany(PaketMakanan::class, 'paket_makanan_menu', 'menu_id', 'paket_makanan_id');
    }
}