@extends('layouts.admin')

@section('title', 'Kelola Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-basket"></i> Kelola Barang</h2>
    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Barang
    </a>
</div>

<!-- Search & Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.barang.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama barang..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="stok_status" class="form-select">
                        <option value="">Semua Stok</option>
                        <option value="tersedia" {{ request('stok_status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ request('stok_status') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
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

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
    @forelse($barangs as $barang)
        <div class="col">
            <div class="card h-100 shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                @if($barang->foto)
                    <img src="{{ asset($barang->foto) }}" class="card-img-top" style="height: 160px; object-fit: cover;" alt="{{ $barang->nama_barang }}">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 160px;">
                        <i class="bi bi-image text-white" style="font-size: 2.5rem;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($barang->nama_barang, 40) }}</h6>
                    <p class="text-primary fw-bold mb-2">Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ $barang->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="bi bi-box"></i> Stok: {{ $barang->stok }}
                        </span>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $barang->updated_at->diffForHumans() }}
                        </small>
                    </div>
                    <a href="{{ route('admin.barang.show', $barang->id) }}" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-inbox"></i> Belum ada barang. <a href="{{ route('admin.barang.create') }}">Tambah barang pertama</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
