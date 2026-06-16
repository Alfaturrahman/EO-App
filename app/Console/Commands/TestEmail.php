<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use App\Mail\PemesananCreated;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email';
    protected $description = 'Test email sending';

    public function handle()
    {
        try {
            $pemesanan = Pemesanan::with('paket')->first();
            
            if (!$pemesanan) {
                $this->error('Tidak ada pesanan ditemukan');
                return;
            }

            $email = $pemesanan->user?->email ?? $pemesanan->guest_email;
            $nama = $pemesanan->user?->name ?? $pemesanan->guest_nama ?? 'Pelanggan';

            if (!$email) {
                $this->error('Email tidak ditemukan pada pesanan ini');
                return;
            }

            $this->info("Mengirim ke: {$email}");
            $this->info("ID Pesanan: {$pemesanan->id}");

            $apiKey = env('BREVO_API_KEY');
            if (!$apiKey) {
                $this->error('BREVO_API_KEY tidak ada di .env');
                return;
            }

            $totalFormatted = 'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.');
            $paketNama = $pemesanan->paket->nama_paket ?? '-';
            $id = $pemesanan->id;

            $response = \Illuminate\Support\Facades\Http::timeout(10)
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
                    'to' => [['email' => $email, 'name' => $nama]],
                    'subject' => "Pesanan Anda Berhasil Dibuat - ID #{$id}",
                    'textContent' => "ID Pesanan: #{$id}\nNama Acara: {$pemesanan->nama_acara}\nPaket: {$paketNama}\nTotal: {$totalFormatted}\nHubungi WhatsApp 087787387484",
                    'htmlContent' => "<p><b>ID Pesanan: #{$id}</b></p><p>Nama Acara: {$pemesanan->nama_acara}</p><p>Total: {$totalFormatted}</p>",
                ]);

            if ($response->successful()) {
                $this->info("✓ Email berhasil dikirim via Brevo API! Status: " . $response->status());
            } else {
                $this->error("✗ Brevo API error: " . $response->status());
                $this->error($response->body());
            }
        } catch (\Exception $e) {
            $this->error("✗ Exception: " . $e->getMessage());
        }
    }
}
