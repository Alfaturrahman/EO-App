@extends('layouts.app')

@section('title', 'Verifikasi OTP')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Verifikasi OTP</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <p class="mb-1"><strong>ID Pesanan:</strong> #{{ $pemesananId }}</p>
                        <p class="mb-0"><strong>Email:</strong> {{ $email }}</p>
                    </div>

                    <p class="text-muted mb-4">Masukkan kode OTP 6 digit yang kami kirim ke email Anda. Kode berlaku 10 menit.</p>

                    <form method="POST" action="{{ route('guest.tracking.verify') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="otp" class="form-label fw-bold">Kode OTP</label>
                            <input
                                type="text"
                                name="otp"
                                id="otp"
                                maxlength="6"
                                inputmode="numeric"
                                class="form-control form-control-lg text-center @error('otp') is-invalid @enderror"
                                placeholder="000000"
                                required
                            >
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Verifikasi OTP
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('guest.tracking.form') }}" class="btn btn-link">Ulangi dari awal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
