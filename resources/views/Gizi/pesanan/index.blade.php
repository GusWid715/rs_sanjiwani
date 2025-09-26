@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container">

    <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Pesanan Masuk</h3>
        <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- TABEL 1: PESANAN PENDING --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0">Pesanan Menunggu Proses (Pending)</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPesanan as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $pesanan->tanggal->format('d M Y, H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.pesanan.process', $pesanan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Proses</button>
                                </form>
                                <form action="{{ route('manager.pesanan.cancel', $pesanan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Batal</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada pesanan yang menunggu proses.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL 2: PESANAN DIPROSES --}}
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Pesanan Sedang Diproses</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prosesPesanan as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $pesanan->tanggal->format('d M Y, H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.pesanan.complete', $pesanan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                                <form action="{{ route('manager.pesanan.cancel', $pesanan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">Batal</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada pesanan yang sedang diproses.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
