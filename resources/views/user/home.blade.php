@extends('layouts.app')

@section('title', 'Katalog Paket - Sonsun EO')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3">Katalog Paket Event</h1>
            <p class="lead text-muted">Pilih paket terbaik untuk acara Anda</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ url('/') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari paket..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="harga_min" class="form-control" placeholder="Harga Min" value="{{ request('harga_min') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="harga_max" class="form-control" placeholder="Harga Max" value="{{ request('harga_max') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <small class="text-muted">
                            Urutkan: 
                            <a href="{{ url('/?sort=harga_total&order=asc') }}" class="text-decoration-none">Harga Terendah</a> | 
                            <a href="{{ url('/?sort=harga_total&order=desc') }}" class="text-decoration-none">Harga Tertinggi</a> | 
                            <a href="{{ url('/?sort=created_at&order=desc') }}" class="text-decoration-none">Terbaru</a>
                            @if(request()->anyFilled(['search', 'harga_min', 'harga_max']))
                                | <a href="{{ url('/') }}" class="text-danger">Reset Filter</a>
                            @endif
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Paket Cards -->
    <div class="row g-4">
        @forelse($pakets as $paket)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    @if($paket->foto)
                        <img src="{{ asset($paket->foto) }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="bi bi-image text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($paket->deskripsi, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h4 class="text-primary mb-0">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</h4>
                            <a href="{{ route('paket.show', $paket->id) }}" class="btn btn-primary">
                                Detail & Pesan <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada paket tersedia
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
