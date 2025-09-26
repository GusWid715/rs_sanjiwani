<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangRawat extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit.
    protected $table = 'ruang_rawat';

    // Mendefinisikan kolom yang dapat diisi.
    protected $fillable = [
        'nama_ruang',
        'lokasi',
    ];

    /**
     * Mendefinisikan relasi "one-to-many" ke model Pesanan.
     * Satu ruang rawat dapat memiliki banyak pesanan.
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'ruang_rawat_id');
    }
}