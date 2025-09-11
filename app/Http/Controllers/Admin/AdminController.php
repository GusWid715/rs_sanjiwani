<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log_aktivitas;
use App\Models\menus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        // ambil parameter
        $q = $request->query('q');
        $role = $request->query('role');

        // build query dengan kondisi only-if-present
        $query = User::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('username', 'like', "%{$q}%")
                    ->orWhere('nama_lengkap', 'like', "%{$q}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        // ordering + paginate
        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // jumlah admin untuk UI (disable delete jika admin terakhir)
        $adminCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'adminCount', 'q', 'role'));
    }

    public function create()
    {
        $allowedRoles = ['admin', 'manager'];
        return view('admin.users.create', compact( 'allowedRoles'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'nama_lengkap' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin','manager'])], // hanya admin & manager
        ]);

        User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $allowedRoles = ['admin', 'manager', 'pasien'];
        return view('admin.users.edit', compact('user', 'allowedRoles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'username' => ['required','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($user->id)],
            'nama_lengkap' => ['nullable','string','max:100'],
            'role' => ['nullable', Rule::in(['admin','manager','pasien'])],
            'password' => ['nullable','string','min:6','confirmed'],
        ];

        $data = $request->validate($rules);

        $user->username = $data['username'];
        $user->nama_lengkap = $data['nama_lengkap'] ?? $user->nama_lengkap;

        if (!empty($data['role'])) {
            $user->role = $data['role'];
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
                return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
