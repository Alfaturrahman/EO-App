<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PemesananController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaketController as AdminPaketController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Test Route - HAPUS SETELAH TESTING
Route::get('/test-admin', function() {
    $admin = Admin::where('email', 'admin@sonsun.com')->first();
    if ($admin) {
        $testPassword = '12345';
        return [
            'email' => $admin->email,
            'name' => $admin->name,
            'test_password' => $testPassword,
            'password_check' => Hash::check($testPassword, $admin->password) ? '✅ BENAR' : '❌ SALAH',
            'hash_preview' => substr($admin->password, 0, 30) . '...',
            'admin_id' => $admin->id
        ];
    }
    return 'Admin tidak ditemukan';
});

Route::get('/test-login', function() {
    $email = 'admin@sonsun.com';
    $password = '12345';
    
    $admin = Admin::where('email', $email)->first();
    
    if (!$admin) {
        return ['error' => 'Admin tidak ditemukan'];
    }
    
    $check = Hash::check($password, $admin->password);
    
    return [
        'step1_admin_found' => '✅ YES',
        'step2_password_check' => $check ? '✅ TRUE' : '❌ FALSE',
        'step3_admin_data' => [
            'id' => $admin->id,
            'email' => $admin->email,
            'name' => $admin->name,
        ],
        'note' => $check ? '✅ LOGIN SEHARUSNYA BERHASIL!' : '❌ Ada masalah dengan hash'
    ];
});

// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes - User
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Routes - Guest can order
Route::get('/paket/{id}', [HomeController::class, 'show'])->name('paket.show');
Route::get('/pemesanan/create/{paket}', [PemesananController::class, 'create'])->name('pemesanan.create');
Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
Route::get('/pemesanan/success/{id}', [PemesananController::class, 'success'])->name('pemesanan.success');
Route::get('/tracking-guest', [PemesananController::class, 'guestTrackingForm'])->name('guest.tracking.form');
Route::get('/tracking-guest/send-otp', function () {
    return redirect()->route('guest.tracking.form')
        ->with('error', 'Silakan isi form tracking untuk mengirim OTP.');
});
Route::post('/tracking-guest/send-otp', [PemesananController::class, 'guestSendOtp'])->name('guest.tracking.send-otp');
Route::get('/tracking-guest/verify', [PemesananController::class, 'guestVerifyForm'])->name('guest.tracking.verify-form');
Route::post('/tracking-guest/verify', [PemesananController::class, 'guestVerifyOtp'])->name('guest.tracking.verify');
Route::get('/tracking-guest/pesanan', [PemesananController::class, 'guestOrderShow'])->name('guest.tracking.order');

// User - Protected Routes (requires login)
Route::middleware('auth')->group(function () {
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/{id}', [PemesananController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{id}/upload-bukti', [PemesananController::class, 'uploadBukti'])->name('pemesanan.upload');
});

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Admin - Protected Routes
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Paket Management
    Route::resource('paket', AdminPaketController::class)->names([
        'index' => 'admin.paket.index',
        'create' => 'admin.paket.create',
        'store' => 'admin.paket.store',
        'show' => 'admin.paket.show',
        'edit' => 'admin.paket.edit',
        'update' => 'admin.paket.update',
        'destroy' => 'admin.paket.destroy',
    ]);
    
    // Barang Management
    Route::resource('barang', BarangController::class)->names([
        'index' => 'admin.barang.index',
        'create' => 'admin.barang.create',
        'store' => 'admin.barang.store',
        'show' => 'admin.barang.show',
        'edit' => 'admin.barang.edit',
        'update' => 'admin.barang.update',
        'destroy' => 'admin.barang.destroy',
    ]);
    
    // Pemesanan Management
    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('admin.pemesanan.index');
    Route::get('/pemesanan/{id}', [AdminPemesananController::class, 'show'])->name('admin.pemesanan.show');
    Route::post('/pemesanan/{id}/validasi-pembayaran', [AdminPemesananController::class, 'validasiPembayaran'])->name('admin.pemesanan.validasi');
    Route::post('/pemesanan/{id}/update-status', [AdminPemesananController::class, 'updateStatus'])->name('admin.pemesanan.status');
});
