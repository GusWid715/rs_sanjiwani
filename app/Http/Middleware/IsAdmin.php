<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * - jika belum login -> redirect ke login
     * - jika bukan admin -> redirect ke home dengan pesan error (atau abort 403, lihat komentar)
     */
    public function handle(Request $request, Closure $next)
    {
        // jika belum terautentikasi
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // cek role, sesuaikan string 'admin' jika kamu pakai nilai lain
        if ($user->role !== 'admin') {
            // Pilihan A: redirect ke homepage dengan pesan (user friendly)
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');

            // Pilihan B (alternatif): abort dengan HTTP 403
            // return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
