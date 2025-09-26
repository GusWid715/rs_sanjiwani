<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena berbeda dari konvensi Laravel.
    protected $table = 'kategori_menu';

    // Mendefinisikan kolom yang boleh diisi secara massal.
    protected $fillable = ['nama_kategori'];

    // Relasi one-to-many: Satu kategori bisa memiliki banyak menu.
    public function menu()
    {
        return $this->hasMany(Menu::class, 'kategori_id');
    }
}