<?php

namespace App\Traits;

use App\Models\log_aktivitas; // import model log_aktivitas
use Illuminate\Support\Facades\Auth; // import Fassad Auth untuk mendapatkan user yang login

trait ActivityLogger
{
    // fungsi untuk mencatat aktivitas
    public function logActivity($aktivitas, $entity, $entity_id)
    {
        // cek apakah ada user yang sedang login
        if (Auth::check()) {
            
            // buat record baru di tabel log_aktivitas
            log_aktivitas::create([
                'user_id' => Auth::id(), // ambil id user yang sedang login
                'aktivitas' => $aktivitas, // deskripsi aktivitas, contoh: "membuat set baru"
                'entity' => $entity, // nama tabel/model terkait, contoh: "sets"
                'entity_id' => $entity_id, // id dari data yang baru dibuat/diubah
            ]);
        }
    }
}