<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsGizi
{
    /**
     * Handle an incoming request.
     * - jika belum login -> redirect ke login
     * - jika bukan gizi -> redirect ke home dengan pesan error (atau abort 403, lihat komentar)
     */
    public function handle(Request $request, Closure $next)
    {
        // jika belum terautentikasi
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // cek role, sesuaikan string 'gizi'
        if ($user->role !== 'gizi') {
            // redirect ke homepage
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman gizi.');
        }

        return $next($request);
    }
}
