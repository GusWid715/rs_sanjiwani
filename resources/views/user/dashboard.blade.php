{{-- resources/views/user/dashboard.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard User - RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .menu-card img {
      width: 100%;
      height: 180px;   /* samakan ukuran gambar */
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>
<body>
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Selamat Datang, {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</h3>
    <div>
      <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-primary">Lihat Pesanan</a>
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
      </form>
    </div>
  </div>

  {{-- Alerts --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <h4 class="mb-3">Daftar Menu</h4>
    <div class="row g-3">
      @foreach($menus as $menu)
        <div class="card">
          <img src="{{ asset('images/' . $menu->image) }}" class="card-img-top" style="height:200px;object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title">{{ $menu->nama_menu }}</h5>
            <p>{{ $menu->deskripsi }}</p>
            <a href="{{ route('user.pesanan.create', ['menu_id' => $menu->id]) }}" class="btn btn-primary">
              Buat Pesanan
            </a>
          </div>
        </div>
      @endforeach
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
