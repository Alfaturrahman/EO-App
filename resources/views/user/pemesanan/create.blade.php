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
                        
                        @guest
                        {{-- Guest Information --}}
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i> <strong>Pemesanan Tanpa Login</strong>
                            <p class="mb-0 mt-2 small">Anda memesan sebagai tamu. Mohon isi data diri Anda dengan lengkap untuk proses pemesanan.</p>
                        </div>

                        <h6 class="mb-3 fw-bold text-primary">Informasi Pemesan</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap *</label>
                            <input type="text" name="guest_nama" class="form-control @error('guest_nama') is-invalid @enderror" 
                                   value="{{ old('guest_nama') }}" required placeholder="Nama lengkap Anda">
                            @error('guest_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror" 
                                       value="{{ old('guest_email') }}" required placeholder="email@example.com">
                                @error('guest_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No. Telepon *</label>
                                <input type="tel" name="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" 
                                       value="{{ old('guest_phone') }}" required placeholder="08123456789">
                                @error('guest_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3 fw-bold text-primary">Detail Acara</h6>
                        @endguest

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Acara *</label>
                            <input type="text" name="nama_acara" class="form-control @error('nama_acara') is-invalid @enderror" 
                                   value="{{ old('nama_acara') }}" required placeholder="Misal: Wedding Budi & Ani">
                            @error('nama_acara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai *</label>
                                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       value="{{ old('tanggal_mulai') }}" required min="{{ date('Y-m-d') }}">
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai *</label>
                                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       value="{{ old('tanggal_selesai') }}" required min="{{ date('Y-m-d') }}">
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Alamat Acara *</label>
                            <textarea name="alamat_acara" class="form-control @error('alamat_acara') is-invalid @enderror" 
                                      rows="3" required placeholder="Alamat lengkap lokasi acara">{{ old('alamat_acara') }}</textarea>
                            @error('alamat_acara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
