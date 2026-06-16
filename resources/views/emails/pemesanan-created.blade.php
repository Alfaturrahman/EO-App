<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: bold; color: #007bff; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #e0e0e0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .detail-label { font-weight: 600; color: #555; }
        .detail-value { color: #333; }
        .price-highlight { font-size: 20px; font-weight: bold; color: #28a745; }
        .alert { background: #e7f3ff; border-left: 4px solid #0066cc; padding: 12px; margin: 15px 0; border-radius: 4px; }
        .alert-title { font-weight: bold; color: #0066cc; margin-bottom: 5px; }
        .button { display: inline-block; padding: 12px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: 600; }
        .button:hover { background: #0056b3; }
        .step-list { counter-reset: steps; list-style: none; padding: 0; margin: 15px 0; }
        .step-list li { counter-increment: steps; padding-left: 30px; margin-bottom: 12px; position: relative; line-height: 1.5; }
        .step-list li:before { content: counter(steps); position: absolute; left: 0; background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0; font-size: 13px; color: #666; }
        .footer p { margin: 5px 0; }
        .order-id { background: #fff3cd; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 14px; font-weight: bold; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Pesanan Anda Berhasil Dibuat!</h1>
            <p>Terima kasih telah memilih Sonsun Event Organizer</p>
        </div>
        
        <div class="content">
            <div class="section">
                <div class="alert">
                    <div class="alert-title">📌 Simpan ID Pesanan Anda</div>
                    <div class="order-id">#{{ $pemesanan->id }}</div>
                    <p style="margin: 8px 0 0 0; font-size: 12px;">Gunakan ID ini untuk tracking pesanan dan komunikasi dengan tim kami</p>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">📋 Detail Pesanan</div>
                <div class="detail-row">
                    <span class="detail-label">Nama Acara:</span>
                    <span class="detail-value">{{ $pemesanan->nama_acara }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Paket yang Dipesan:</span>
                    <span class="detail-value">{{ $pemesanan->paket->nama_paket ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lokasi Acara:</span>
                    <span class="detail-value">{{ $pemesanan->alamat_acara }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Harga:</span>
                    <span class="detail-value price-highlight">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran:</span>
                    <span class="detail-value" style="color: #ff9800; font-weight: 600;">⏳ Menunggu Pembayaran</span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">💰 Langkah Pembayaran</div>
                <ol class="step-list">
                    <li>Transfer pembayaran ke rekening yang telah kami sediakan (akan diinformasikan melalui WhatsApp)</li>
                    <li>Kirimkan bukti pembayaran melalui WhatsApp ke <strong>087787387484</strong></li>
                    <li>Sertakan <strong>ID Pesanan: #{{ $pemesanan->id }}</strong> dalam pesan Anda</li>
                    <li>Tim kami akan memverifikasi pembayaran dalam 1x24 jam</li>
                    <li>Status pesanan akan diperbarui setelah verifikasi selesai</li>
                </ol>
            </div>
            
            <div class="section">
                <div class="section-title">👥 Hubungi Kami</div>
                <p>Jika Anda memiliki pertanyaan atau butuh bantuan, silakan hubungi kami:</p>
                <p style="margin: 10px 0;">
                    <strong>WhatsApp:</strong> <a href="https://wa.me/6287787387484" style="color: #25d366; text-decoration: none;">087787387484</a><br>
                    <strong>Email:</strong> admin@sonsun.com
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ url('/tracking-guest') }}" class="button">Tracking Pesanan Saya</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Tim Sonsun Event Organizer</strong></p>
            <p>&copy; 2024 Sonsun Event Organizer. Semua hak dilindungi.</p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;">
                Email ini dikirim otomatis. Jangan balas email ini.
            </p>
        </div>
    </div>
</body>
</html>
