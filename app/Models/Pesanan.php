<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena 'pesanan' adalah bentuk tunggal.
    protected $table = 'pesanan';

    // Mendefinisikan kolom yang dapat diisi melalui metode mass assignment.
    protected $fillable = [
        'ruang_rawat_id',
        'paket_makanan_id',
        'tanggal',
        'status',
        'alasan_batal',
    ];

    // Melakukan casting tipe data untuk atribut tertentu.
    protected $casts = [
        'tanggal' => 'datetime', // Mengubah kolom 'tanggal' menjadi objek Carbon.
    ];

    public function paketMakanan()
    {
        return $this->belongsTo(PaketMakanan::class, 'paket_makanan_id');
    }

    public function ruangRawat()
    {
        // Diasumsikan Anda akan memiliki model RuangRawat di masa depan.
        return $this->belongsTo(RuangRawat::class, 'ruang_rawat_id');
    }
}