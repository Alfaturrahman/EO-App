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
            $pemesanan = Pemesanan::first();
            
            if (!$pemesanan) {
                $this->error('Tidak ada pesanan ditemukan');
                return;
            }

            // Get email from user or guest
            $email = $pemesanan->user?->email ?? $pemesanan->guest_email ?? 'no-email@example.com';
            
            $this->info("Sending email to: {$email}");
            $this->info("Pemesanan ID: {$pemesanan->id}");
            $this->info("Nama Acara: {$pemesanan->nama_acara}");
            
            Mail::to($email)->send(new PemesananCreated($pemesanan));
            
            $this->info("✓ Email berhasil dikirim!");
        } catch (\Exception $e) {
            $this->error("✗ Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . " Line: " . $e->getLine());
        }
    }
}
