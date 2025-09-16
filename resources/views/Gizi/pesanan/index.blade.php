@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Pesanan Masuk (Pending)</h3>
                <a href="{{ route('gizi.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pasien</th>
                            <th>Menu</th>
                            <th>Set Makanan</th> {{-- TAMBAHKAN HEADER INI --}}
                            <th class="text-center">Jumlah</th>
                            <th>Ruangan</th>
                            <th>Waktu Pesan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans as $pesanan)
                            <tr>
                                <td><strong>{{ $pesanan->user->name ?? 'N/A' }}</strong></td>
                                <td>{{ $pesanan->menu->nama_menu ?? 'N/A' }}</td>
                                {{-- TAMBAHKAN KOLOM INI --}}
                                <td>
                                    <span class="text">{{ $pesanan->menu->set->nama_set ?? 'Tanpa Set' }}</span>
                                </td>
                                <td class="text-center">{{ $pesanan->jumlah }}</td>
                                <td>{{ $pesanan->ruangan }}</td>
                                <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('gizi.pesanan.approve', $pesanan->id) }}" method="POST" onsubmit="return confirm('Selesaikan pesanan ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Selesaikan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Ubah colspan menjadi 7 --}}
                                <td colspan="7" class="text-center py-4">
                                    Tidak ada pesanan yang menunggu persetujuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection