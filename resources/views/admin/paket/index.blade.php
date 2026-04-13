@extends('layouts.admin')

@section('title', 'Kelola Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Kelola Paket</h2>
    <a href="{{ route('admin.paket.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Paket
    </a>
</div>

<!-- Search & Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.paket.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="harga_min" class="form-control" placeholder="Harga Min" value="{{ request('harga_min') }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="harga_max" class="form-control" placeholder="Harga Max" value="{{ request('harga_max') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @forelse($pakets as $paket)
        <div class="col">
            <div class="card h-100 shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                @if($paket->foto)
                    <img src="{{ asset($paket->foto) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $paket->nama_paket }}">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $paket->nama_paket }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($paket->deskripsi, 80) }}</p>
                    <h5 class="text-primary mb-3">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $paket->updated_at->diffForHumans() }}
                        </small>
                        <a href="{{ route('admin.paket.show', $paket->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-inbox"></i> Belum ada paket. <a href="{{ route('admin.paket.create') }}">Tambah paket pertama</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
