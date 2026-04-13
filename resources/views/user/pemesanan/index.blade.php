@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-clipboard-check"></i> Pesanan Saya</h2>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pemesanan.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama acara..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status_pembayaran" class="form-select">
                            <option value="">Semua Status Bayar</option>
                            <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status_pengambilan" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="belum_diambil" {{ request('status_pengambilan') == 'belum_diambil' ? 'selected' : '' }}>Belum Diambil</option>
                            <option value="dalam_penggunaan" {{ request('status_pengambilan') == 'dalam_penggunaan' ? 'selected' : '' }}>Dalam Penggunaan</option>
                            <option value="selesai" {{ request('status_pengambilan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($pemesanans as $p)
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">{{ $p->nama_acara }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-box-seam"></i> {{ $p->paket->nama_paket ?? '-' }}<br>
                                    <i class="bi bi-calendar"></i> {{ $p->tanggal_mulai->format('d M Y') }} - {{ $p->tanggal_selesai->format('d M Y') }}<br>
                                    <i class="bi bi-geo-alt"></i> {{ Str::limit($p->alamat_acara, 50) }}
                                </p>
                                <div>
                                    <span class="badge bg-{{ $p->status_pembayaran == 'lunas' ? 'success' : 'warning' }}">
                                        {{ ucfirst($p->status_pembayaran) }}
                                    </span>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $p->status_pengambilan)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <h4 class="text-primary mb-3">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</h4>
                                <a href="{{ route('pemesanan.show', $p->id) }}" class="btn btn-primary btn-sm">
                                    Detail Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada pemesanan
                </div>
                <div class="text-center">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-cart-plus"></i> Mulai Pesan
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
