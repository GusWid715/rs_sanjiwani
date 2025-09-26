<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsManager
{
    // Handle an incoming request.
    // jika belum login -> redirect ke login
    // jika bukan manager -> redirect ke home dengan pesan error 
    public function handle(Request $request, Closure $next)
    {
        // jika belum terautentikasi
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // cek role, sesuaikan string 'gizi'
        if ($user->role !== 'manager') {
            // redirect ke homepage
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman gizi.');
        }

        return $next($request);
    }
}
