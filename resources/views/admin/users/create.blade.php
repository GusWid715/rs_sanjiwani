{{-- resources/views/admin/users/create.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Buat Akun - Admin | RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Buat Akun Baru (Admin / Manager)</h3>
    <div>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.users.store') }}" method="POST" class="card card-body">
    @csrf

    <div class="mb-3">
      <label class="form-label">Username <span class="text-danger">*</span></label>
      <input type="text" name="username" value="{{ old('username') }}" class="form-control" required maxlength="50">
      <small class="text-muted">Hanya huruf, angka, dash, underscore (alpha_dash).</small>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control" maxlength="100">
    </div>

    <div class="mb-3">
      <label class="form-label">Role <span class="text-danger">*</span></label>
      <select name="role" class="form-select" required>
        <option value="">-- Pilih Role --</option>
        @foreach($allowedRoles as $r)
          <option value="{{ $r }}" {{ old('role')===$r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
        @endforeach
      </select>
      <small class="form-text text-muted">Hanya boleh membuat akun dengan role: admin atau manager.</small>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control" required minlength="6">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-success" type="submit">Buat Akun</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
