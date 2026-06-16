<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PemesananCreated;
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

        // Send email notification via Brevo API
        $emailTo = Auth::check() ? Auth::user()->email : $pemesanan->guest_email;
        $namaTo = Auth::check() ? Auth::user()->name : ($pemesanan->guest_nama ?? 'Pelanggan');
        if ($emailTo) {
            try {
                $apiKey = env('BREVO_API_KEY');
                if (!$apiKey) {
                    throw new \RuntimeException('BREVO_API_KEY is not configured.');
                }

                $totalFormatted = 'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.');
                $paketNama = $pemesanan->paket->nama_paket ?? '-';

                $htmlContent = "
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
                  <div style='background:#007bff;color:white;padding:25px;text-align:center;border-radius:8px 8px 0 0;'>
                    <h1 style='margin:0;font-size:24px;'>&#10003; Pesanan Berhasil Dibuat!</h1>
                    <p style='margin:5px 0 0;opacity:.9;'>Sonsun Event Organizer</p>
                  </div>
                  <div style='background:#f9f9f9;padding:25px;border:1px solid #e0e0e0;'>
                    <p>Halo <strong>{$namaTo}</strong>,</p>
                    <p>Terima kasih telah melakukan pemesanan. Berikut detail pesanan Anda:</p>
                    <div style='background:#fff3cd;border:1px solid #ffc107;border-radius:5px;padding:12px;margin:15px 0;'>
                      <strong>&#128204; ID Pesanan: #{$pemesanan->id}</strong><br>
                      <small>Simpan ID ini untuk tracking pesanan Anda</small>
                    </div>
                    <table style='width:100%;border-collapse:collapse;margin:15px 0;'>
                      <tr style='background:#f0f0f0;'><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Nama Acara</td><td style='padding:10px;border:1px solid #ddd;'>{$pemesanan->nama_acara}</td></tr>
                      <tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Paket</td><td style='padding:10px;border:1px solid #ddd;'>{$paketNama}</td></tr>
                      <tr style='background:#f0f0f0;'><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Lokasi Acara</td><td style='padding:10px;border:1px solid #ddd;'>{$pemesanan->alamat_acara}</td></tr>
                      <tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Total Harga</td><td style='padding:10px;border:1px solid #ddd;color:#28a745;font-weight:bold;'>{$totalFormatted}</td></tr>
                      <tr style='background:#f0f0f0;'><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Status</td><td style='padding:10px;border:1px solid #ddd;color:#ff9800;'>Menunggu Pembayaran</td></tr>
                    </table>
                    <h3>Langkah Selanjutnya:</h3>
                    <ol>
                      <li>Kirim bukti pembayaran melalui WhatsApp ke <strong>087787387484</strong></li>
                      <li>Sertakan <strong>ID Pesanan: #{$pemesanan->id}</strong> dalam pesan</li>
                      <li>Tim kami akan memverifikasi dalam 1x24 jam</li>
                    </ol>
                    <div style='text-align:center;margin:20px 0;'>
                      <a href='https://wa.me/6287787387484' style='background:#25d366;color:white;padding:12px 25px;text-decoration:none;border-radius:5px;font-weight:bold;'>&#128242; Hubungi via WhatsApp</a>
                    </div>
                  </div>
                  <div style='background:#f0f0f0;padding:15px;text-align:center;font-size:12px;color:#666;border-radius:0 0 8px 8px;'>
                    Tim Sonsun Event Organizer &bull; Email otomatis, jangan dibalas
                  </div>
                </div>";

                $textContent = "Pesanan Anda Berhasil Dibuat!\n\nID Pesanan: #{$pemesanan->id}\nNama Acara: {$pemesanan->nama_acara}\nPaket: {$paketNama}\nTotal: {$totalFormatted}\nStatus: Menunggu Pembayaran\n\nKirim bukti pembayaran via WhatsApp ke 087787387484 dengan menyertakan ID Pesanan #{$pemesanan->id}.\n\nTim Sonsun Event Organizer";

                Http::timeout(10)
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
                        'to' => [['email' => $emailTo, 'name' => $namaTo]],
                        'subject' => "Pesanan Anda Berhasil Dibuat - ID #{$pemesanan->id}",
                        'htmlContent' => $htmlContent,
                        'textContent' => $textContent,
                    ]);

                Log::info('Order confirmation email sent', ['to' => $emailTo, 'pemesanan_id' => $pemesanan->id]);
            } catch (\Exception $e) {
                Log::error('Error sending order confirmation email: ' . $e->getMessage(), ['pemesanan_id' => $pemesanan->id]);
            }
        }

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
                    'replyTo' => [
                        'email' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                        'name' => env('MAIL_FROM_NAME', 'Sonsun EO'),
                    ],
                    'to' => [
                        [
                            'email' => $validated['guest_email'],
                        ],
                    ],
                    'subject' => 'Kode OTP Tracking Pesanan Sonsun EO',
                    'textContent' => "Kode OTP tracking pesanan Anda: {$otp}\n\nKode berlaku 10 menit. Jangan bagikan kode ini ke siapa pun.",
                    'htmlContent' => "<p>Kode OTP tracking pesanan Anda: <strong>{$otp}</strong></p><p>Kode berlaku 10 menit. Jangan bagikan kode ini ke siapa pun.</p>",
                ]);

            if (!$response->successful()) {
                Log::error('Brevo API rejected OTP request', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'from_email' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                    'to_email' => $validated['guest_email'],
                    'pemesanan_id' => $pemesanan->id,
                ]);

                throw new \RuntimeException('Brevo API error: ' . $response->status() . ' ' . $response->body());
            }

            Log::info('Brevo API accepted OTP request', [
                'status' => $response->status(),
                'body' => $response->body(),
                'to_email' => $validated['guest_email'],
                'pemesanan_id' => $pemesanan->id,
            ]);
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
        
        // Check access: user can only upload their own order
        if ($pemesanan->user_id && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);
        
        if ($request->hasFile('bukti_pembayaran')) {
            // Replace old proof file if exists
            if ($pemesanan->bukti_pembayaran) {
                $oldPath = public_path($pemesanan->bukti_pembayaran);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

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
