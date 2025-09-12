<?php

namespace App\Http\Controllers\Admin; 
// Menentukan namespace agar controller ini masuk ke dalam folder Admin

use App\Http\Controllers\Controller; 
// Mengimpor base Controller bawaan Laravel

use App\Models\Log_aktivitas; 
// Model Log_aktivitas (sepertinya untuk mencatat aktivitas user, walau belum dipakai di kode ini)

use App\Models\menus; 
// Model menus (kemungkinan untuk menu navigasi admin, belum dipakai di kode ini)

use App\Models\User; 
// Model User untuk query ke tabel users

use Illuminate\Http\Request; 
// Digunakan untuk menangani HTTP request

use Illuminate\Validation\Rule; 
// Untuk membuat aturan validasi yang lebih fleksibel

use Illuminate\Support\Facades\Hash; 
// Digunakan untuk hashing password (misalnya Hash::make)

use Illuminate\Support\Facades\Auth; 
// Untuk autentikasi user yang sedang login (Auth::id, dsb)

class AdminController extends Controller
{
    // ================= MENAMPILKAN LIST USER =================
    public function index(Request $request)
    {
        // Ambil query string dari URL (parameter pencarian/filter)
        $q = $request->query('q'); 
        $role = $request->query('role');

        // Membuat query builder untuk model User
        $query = User::query();

        // Jika ada pencarian (q), filter berdasarkan username atau nama_lengkap
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('username', 'like', "%{$q}%")
                    ->orWhere('nama_lengkap', 'like', "%{$q}%");
            });
        }

        // Jika ada filter role, tambahkan kondisi where role
        if ($role) {
            $query->where('role', $role);
        }

        // Urutkan user berdasarkan id terbaru (desc) dan paginate 10 per halaman
        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Hitung jumlah user yang punya role 'admin'
        $adminCount = User::where('role', 'admin')->count();

        // Kirim data ke view 'admin.users.index'
        return view('admin.users.index', compact('users', 'adminCount', 'q', 'role'));
    }

    // ================= FORM TAMBAH USER =================
    public function create()
    {
        // Tentukan role yang boleh dipilih saat membuat user baru
        $allowedRoles = ['admin', 'manager'];

        // Kirim data ke view create
        return view('admin.users.create', compact('allowedRoles'));
    }

    // ================= FORM DETAIL USER =================
    public function show(User $user)
    {
        // Tampilkan detail user tertentu
        return view('admin.users.show', compact('user'));
    }

    // ================= SIMPAN DATA USER BARU =================
    public function store(Request $request)
    {
        // Validasi input dari form create
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'nama_lengkap' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['admin','manager'])], // role harus admin/manager
        ]);

        // Simpan data user baru ke database
        User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password), // password di-hash
            'role' => $request->role,
        ]);

        // Redirect ke halaman list user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // ================= FORM EDIT USER =================
    public function edit(User $user)
    {
        // Tentukan role yang boleh dipilih saat edit user
        $allowedRoles = ['admin', 'manager', 'pasien'];

        // Kirim data ke view edit
        return view('admin.users.edit', compact('user', 'allowedRoles'));
    }

    // ================= UPDATE DATA USER =================
    public function update(Request $request, User $user)
    {
        // Aturan validasi
        $rules = [
            'username' => ['required','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($user->id)],
            'nama_lengkap' => ['nullable','string','max:100'],
            'role' => ['nullable', Rule::in(['admin','manager','pasien'])],
            'password' => ['nullable','string','min:6','confirmed'],
        ];

        // Validasi input
        $data = $request->validate($rules);

        // Update username
        $user->username = $data['username'];

        // Update nama_lengkap jika ada
        $user->nama_lengkap = $data['nama_lengkap'] ?? $user->nama_lengkap;

        // Update role jika ada input role
        if (!empty($data['role'])) {
            $user->role = $data['role'];
        }

        // Update password jika ada input password
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Simpan perubahan ke database
        $user->save();

        // Redirect ke halaman list user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    // ================= FORM HAPUS USER =================
    public function destroy(User $user)
    {
        // Cegah user menghapus akun sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus user
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
