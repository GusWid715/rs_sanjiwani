<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RS Sanjiwani — Sistem Makanan Rumah Sakit</title>
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">RS Sanjiwani</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button class="btn btn-link nav-link" type="submit">Logout</button>
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<header class="py-5">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6 text-center text-lg-start">
        <h1 class="display-5 fw-bold mb-3">Menu Kantin RS Sanjiwani — Menu Kantin</h1>
        <h5 class="text-muted mb-3">Makanan akan diantarkan ke ruangan pasien yang memesan.</h5>

        <p class="lead text-muted mb-4">
          Pilih menu sehat yang tersedia, pesan untuk pasien atau pun keluarga, dan tim akan mengantar langsung ke ruangan tujuan.
        </p>

        <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
          <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk / Login</a>
          <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">Daftar Akun</a>
        </div>
      </div>

      <div class="col-lg-6 text-center">
        <div class="p-3 bg-white rounded shadow-sm">
          <img src="{{ asset('images/logo.jpg') }}" alt="Contoh Makanan Kantin" class="img-fluid rounded" style="max-height: 600px; object-fit:cover; width:100%;">
        </div>
      </div>
    </div>
  </div>
</header>

<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="h3">Contoh Menu dari Kantin</h2>
      <p class="text-muted">Berikut contoh makanan yang biasanya tersedia di kantin.</p>
    </div>

    {{-- Gallery: 6 contoh makanan --}}
    <div class="row g-4">
      @php
        $samples = [
          ['img'=>'images/nasikuning.jpg','title'=>'Nasi Kuning','desc'=>'Nasi kuning gurih dengan lauk ayam suwir, telur, dan sambal khas'],
          ['img'=>'images/nasigoreng.jpg','title'=>'Nasi Goreng Sehat','desc'=>'Nasi goreng rendah minyak & sayuran'],
          ['img'=>'images/nasicampur.jpg','title'=>'Nasi Campur','desc'=>'Nasi putih lengkap dengan sayur, ayam goreng, tempe, tahu, dan sambal'],
          ['img'=>'images/bubur.jpg','title'=>'Bubur Ayam','desc'=>'Bubur ayam dengan ayam suwir & bawang goreng'],
          ['img'=>'images/lalapan.jpg','title'=>'Lalapan','desc'=>'Sayuran segar dengan ayam goreng atau ikan, disajikan bersama sambal'],
          ['img'=>'images/capcay.jpg','title'=>'Capcay Sayur','desc'=>'Capcay sayur sehat'],
        ];
      @endphp

      @foreach($samples as $s)
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 shadow-sm">
          <div style="height:180px; overflow:hidden;">
            {{-- pakai asset lokal, kalau belum ada akan tampil broken image; bisa ganti placeholder --}}
            <img src="{{ asset($s['img']) }}" alt="{{ $s['title'] }}" class="img-fluid w-100" style="object-fit:cover; height:100%;">
          </div>
          <div class="card-body">
            <h5 class="card-title mb-1">{{ $s['title'] }}</h5>
            <p class="card-text text-muted small mb-2">{{ $s['desc'] }}</p>
            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Pesan Sekarang</a>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <h3 class="fw-semibold">Proses Pemesanan & Pengantaran</h3>
        <p class="text-muted">Setelah pemesanan dilakukan, status pesanan akan terlihat di dashboard. Tim kantin akan menyiapkan dan mengantar pesanan ke ruangan pasien sesuai data pemesanan.</p>
        <ul class="list-unstyled text-muted">
          <li>Menu dari kantin RS Sanjiwani</li>
          <li>Pengantaran ke ruangan pasien</li>
          <li>Status pesanan: pending → proses → selesai</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<footer class="py-4 bg-dark text-white">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
    <div>© {{ date('Y') }} RS Sanjiwani</div>
    <div class="text-muted small">Dibuat untuk tugas PKL — Fakultas Informatika</div>
  </div>
</footer>

</body>
</html>
