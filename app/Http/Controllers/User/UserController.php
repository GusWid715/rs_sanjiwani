<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\menus;

class UserController extends Controller
{
    // Dashboard: tampilkan semua menu
    public function index()
    {
        $menus = menus::with('kategori')->latest()->get();
        return view('user.dashboard', compact('menus'));
    }
}
