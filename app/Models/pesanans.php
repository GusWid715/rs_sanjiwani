<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pesanans extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $fillable = [
        'tanggal',
        'status',
    ];

    protected $dates = ['tanggal'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(detail_pesanans::class, 'pesanan_id');
    }
}
