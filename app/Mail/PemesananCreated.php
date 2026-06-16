<?php

namespace App\Mail;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PemesananCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pemesanan $pemesanan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Anda Berhasil Dibuat - ID #' . $this->pemesanan->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.pemesanan-created',
            with: [
                'pemesanan' => $this->pemesanan,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

