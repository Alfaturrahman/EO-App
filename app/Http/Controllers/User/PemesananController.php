<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PemesananController extends Controller
{
    private const GUEST_TRACKING_SESSION_KEY = 'guest_tracking';

    public function index()
    {
        $query = Pemesanan::where('user_id', Auth::id())->with('paket');

        // Filter status
        if (request('status_pembayaran')) {
            $query->where('status_pembayaran', request('status_pembayaran'));
        }
        if (request('status_pengambilan')) {
            $query->where('status_pengambilan', request('status_pengambilan'));
        }

        // Search
        if (request('search')) {
            $query->where('nama_acara', 'ilike', '%' . request('search') . '%');
        }

        $pemesanans = $query->orderBy('created_at', 'desc')->get();
        
        return view('user.pemesanan.index', compact('pemesanans'));
    }

    public function create($paketId)
    {
        $paket = Paket::findOrFail($paketId);
        return view('user.pemesanan.create', compact('paket'));
    }

    public function store(Request $request)
    {
        $rules = [
            'paket_id' => 'required|exists:pakets,id',
            'nama_acara' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_acara' => 'required|string',
        ];

        // If guest (not logged in), require guest info
        if (!Auth::check()) {
            $rules['guest_nama'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $paket = Paket::findOrFail($validated['paket_id']);
        
        $pemesananData = [
            'paket_id' => $validated['paket_id'],
            'nama_acara' => $validated['nama_acara'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alamat_acara' => $validated['alamat_acara'],
            'total_harga' => $paket->harga_total,
            'status_pembayaran' => 'pending',
            'status_pengambilan' => 'belum_diambil',
        ];

        // Set user_id if logged in, otherwise set guest info
        if (Auth::check()) {
            $pemesananData['user_id'] = Auth::id();
        } else {
            $pemesananData['guest_nama'] = $validated['guest_nama'];
            $pemesananData['guest_email'] = $validated['guest_email'];
            $pemesananData['guest_phone'] = $validated['guest_phone'];
        }

        $pemesanan = Pemesanan::create($pemesananData);

        // Redirect with order ID for guest users
        return redirect()->route('pemesanan.success', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan simpan ID pesanan Anda: #' . $pemesanan->id);
    }

    public function success($id)
    {
        $pemesanan = Pemesanan::with('paket')->findOrFail($id);
        return view('user.pemesanan.success', compact('pemesanan'));
    }

    public function guestTrackingForm()
    {
        return view('user.pemesanan.guest-tracking');
    }

    public function guestSendOtp(Request $request)
    {
        $validated = $request->validate([
            'pemesanan_id' => 'required|integer|min:1',
            'guest_email' => 'required|email',
        ]);

        $pemesanan = Pemesanan::where('id', $validated['pemesanan_id'])
            ->whereNull('user_id')
            ->where('guest_email', $validated['guest_email'])
            ->first();

        if (!$pemesanan) {
            return back()->withInput()->with('error', 'Data pesanan tidak ditemukan. Pastikan ID pesanan dan email benar.');
        }

        $tracking = $request->session()->get(self::GUEST_TRACKING_SESSION_KEY);
        if (is_array($tracking) && isset($tracking['last_sent_at']) && now()->timestamp - (int) $tracking['last_sent_at'] < 60) {
            return back()->withInput()->with('error', 'Kode OTP baru bisa dikirim ulang setelah 60 detik.');
        }

        $otp = (string) random_int(100000, 999999);

        try {
            $apiKey = env('BREVO_API_KEY');
            if (!$apiKey) {
                throw new \RuntimeException('BREVO_API_KEY is not configured.');
            }

            $response = Http::timeout(10)
                ->withHeaders([
                    'accept' => 'application/json',
                    'api-key' => $apiKey,
                    'content-type' => 'application/json',
                ])
                ->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'name' => env('MAIL_FROM_NAME', 'Sonsun EO'),
                        'email' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                    ],
                    'to' => [
                        [
                            'email' => $validated['guest_email'],
                        ],
                    ],
                    'subject' => 'Kode OTP Tracking Pesanan Sonsun EO',
                    'textContent' => "Kode OTP tracking pesanan Anda: {$otp}\n\nKode berlaku 10 menit. Jangan bagikan kode ini ke siapa pun.",
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException('Brevo API error: ' . $response->status() . ' ' . $response->body());
            }
        } catch (Throwable $e) {
            Log::error('Guest OTP send failed', [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'pemesanan_id' => $pemesanan->id,
                'guest_email' => $validated['guest_email'],
            ]);

            return back()->withInput()->with('error', 'Gagal mengirim OTP ke email. Silakan coba lagi.');
        }

        $request->session()->put(self::GUEST_TRACKING_SESSION_KEY, [
            'pemesanan_id' => $pemesanan->id,
            'guest_email' => $validated['guest_email'],
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'attempts' => 0,
            'last_sent_at' => now()->timestamp,
            'verified' => false,
        ]);

        return redirect()->route('guest.tracking.verify-form')
            ->with('success', 'Kode OTP sudah dikirim ke email Anda.');
    }

    public function guestVerifyForm(Request $request)
    {
        $tracking = $request->session()->get(self::GUEST_TRACKING_SESSION_KEY);

        if (!is_array($tracking) || empty($tracking['pemesanan_id']) || empty($tracking['guest_email'])) {
            return redirect()->route('guest.tracking.form')->with('error', 'Silakan isi data tracking terlebih dahulu.');
        }

        return view('user.pemesanan.guest-verify', [
            'email' => $tracking['guest_email'],
            'pemesananId' => $tracking['pemesanan_id'],
        ]);
    }

    public function guestVerifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $tracking = $request->session()->get(self::GUEST_TRACKING_SESSION_KEY);
        if (!is_array($tracking) || empty($tracking['otp_hash']) || empty($tracking['expires_at'])) {
            return redirect()->route('guest.tracking.form')->with('error', 'Sesi OTP tidak valid. Silakan kirim OTP ulang.');
        }

        if (now()->timestamp > (int) $tracking['expires_at']) {
            $request->session()->forget(self::GUEST_TRACKING_SESSION_KEY);
            return redirect()->route('guest.tracking.form')->with('error', 'Kode OTP sudah kedaluwarsa. Silakan kirim OTP ulang.');
        }

        $attempts = (int) ($tracking['attempts'] ?? 0);
        if ($attempts >= 5) {
            $request->session()->forget(self::GUEST_TRACKING_SESSION_KEY);
            return redirect()->route('guest.tracking.form')->with('error', 'Terlalu banyak percobaan OTP. Silakan mulai lagi.');
        }

        if (!Hash::check($validated['otp'], $tracking['otp_hash'])) {
            $tracking['attempts'] = $attempts + 1;
            $request->session()->put(self::GUEST_TRACKING_SESSION_KEY, $tracking);
            return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
        }

        $tracking['verified'] = true;
        $tracking['otp_hash'] = null;
        $tracking['attempts'] = 0;
        $request->session()->put(self::GUEST_TRACKING_SESSION_KEY, $tracking);

        return redirect()->route('guest.tracking.order');
    }

    public function guestOrderShow(Request $request)
    {
        $tracking = $request->session()->get(self::GUEST_TRACKING_SESSION_KEY);

        if (!is_array($tracking) || empty($tracking['verified']) || empty($tracking['pemesanan_id']) || empty($tracking['guest_email'])) {
            return redirect()->route('guest.tracking.form')->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        $pemesanan = Pemesanan::with('paket')
            ->where('id', $tracking['pemesanan_id'])
            ->whereNull('user_id')
            ->where('guest_email', $tracking['guest_email'])
            ->first();

        if (!$pemesanan) {
            $request->session()->forget(self::GUEST_TRACKING_SESSION_KEY);
            return redirect()->route('guest.tracking.form')->with('error', 'Pesanan tidak ditemukan. Silakan cek kembali data Anda.');
        }

        return view('user.pemesanan.guest-show', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('paket')->findOrFail($id);
        
        // Check access: either owns the order or is guest order (no user_id)
        if ($pemesanan->user_id && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.pemesanan.show', compact('pemesanan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        if ($pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);
        
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            
            $pemesanan->update([
                'bukti_pembayaran' => 'uploads/bukti/' . $filename,
            ]);
        }
        
        return back()->with('success', 'Bukti pembayaran berhasil diupload');
    }
}
