<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class log_aktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'entity',
        'entity_id',
        'waktu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
