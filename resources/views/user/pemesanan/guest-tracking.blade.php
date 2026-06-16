@extends('layouts.app')

@section('title', 'Tracking Pesanan Guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-search"></i> Cek Status Pesanan Guest</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Masukkan ID pesanan dan email yang dipakai saat pemesanan. Kami akan kirim OTP ke email tersebut.</p>

                    <form method="POST" action="{{ route('guest.tracking.send-otp') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="pemesanan_id" class="form-label fw-bold">ID Pesanan</label>
                            <input
                                type="number"
                                name="pemesanan_id"
                                id="pemesanan_id"
                                class="form-control @error('pemesanan_id') is-invalid @enderror"
                                value="{{ old('pemesanan_id') }}"
                                min="1"
                                placeholder="Contoh: 123"
                                required
                            >
                            @error('pemesanan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="guest_email" class="form-label fw-bold">Email Pemesan</label>
                            <input
                                type="email"
                                name="guest_email"
                                id="guest_email"
                                class="form-control @error('guest_email') is-invalid @enderror"
                                value="{{ old('guest_email') }}"
                                placeholder="email@example.com"
                                required
                            >
                            @error('guest_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-envelope-check"></i> Kirim OTP ke Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
