{{-- resources/views/admin/users/edit.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit User - Admin | RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit User: {{ $user->username }}</h3>
    <div>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="card card-body">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Username <span class="text-danger">*</span></label>
      <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" required maxlength="50">
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="form-control" maxlength="100">
    </div>

  <div class="form-group mb-3">
      <label for="role">Role</label>
      <select name="role" class="form-control" required>
          @foreach($allowedRoles as $role)
              <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                  {{ ucfirst($role) }}
              </option>
          @endforeach
      </select>
  </div>

    <hr>

    <div class="mb-3">
      <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
      <input type="password" name="password" class="form-control" minlength="6">
    </div>
    <div class="mb-3">
      <label class="form-label">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" class="form-control" minlength="6">
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>

  {{-- Hapus --}}
  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger">Hapus User</button>
  </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
