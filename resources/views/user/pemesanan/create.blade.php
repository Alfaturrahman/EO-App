@extends('layouts.app')

@section('title', 'Form Pemesanan')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('paket.show', $paket->id) }}">{{ $paket->nama_paket }}</a></li>
            <li class="breadcrumb-item active">Form Pemesanan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-plus"></i> Form Pemesanan</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('pemesanan.store') }}">
                        @csrf
                        <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Acara *</label>
                            <input type="text" name="nama_acara" class="form-control" required placeholder="Misal: Wedding Budi & Ani">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai *</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai *</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Acara *</label>
                            <textarea name="alamat_acara" class="form-control" rows="3" required placeholder="Alamat lengkap lokasi acara"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Proses Pemesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Ringkasan Pesanan</h6>
                </div>
                <div class="card-body">
                    <p class="fw-bold">{{ $paket->nama_paket }}</p>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Harga Paket:</span>
                        <span class="fw-bold">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold text-primary">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
