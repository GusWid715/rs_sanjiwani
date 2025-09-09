<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // tampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    // edit user
    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    // update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'role' => 'required|string|in:admin, pasien',
        ]);

        $user->update($request->only('username','nama_lengkap','role'));

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    // hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
