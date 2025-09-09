<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard Pasien - RS Sanjiwani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">

    {{-- Navbar sederhana --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">RS Sanjiwani</a>

        <div class="d-flex">
          {{-- Tombol logout --}}
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
              Logout
            </button>
          </form>
        </div>
      </div>
    </nav>

    {{-- Konten dashboard --}}
    <h2>Selamat datang, {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</h2>

    <p class="text-muted">Anda login sebagai <strong>{{ Auth::user()->role }}</strong></p>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pesanan Saya</h5>
                    <p class="card-text">Lihat dan kelola pesanan Anda.</p>
                    <a href="{{ route('user.pesanan.index') }}" class="btn btn-primary">Lihat Pesanan</a>
                    <a href="{{ route('user.pesanan.create') }}" class="btn btn-success">Buat Pesanan Baru</a>
                </div>
            </div>
        </div>

        {{-- Tambahan menu lain sesuai kebutuhan --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Profil</h5>
                    <p class="card-text">Informasi akun Anda.</p>
                    <p><strong>Nama:</strong> {{ Auth::user()->nama_lengkap }}</p>
                    <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
