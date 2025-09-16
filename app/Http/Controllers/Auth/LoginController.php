<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function username()
    {
        return 'name';
    }

    /**
     * Override redirect setelah login
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->role === 'gizi') {
            return '/gizi'; // nanti kita buat route admin
        }

        if ($user->role === 'pasien') {
        return '/user/dashboard'; // route pasien
    }

        return '/home'; 
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
