@component('mail::message')
# Pesanan Anda Berhasil Dibuat!

Terima kasih telah melakukan pemesanan di **Sonsun Event Organizer**.

---

## Detail Pesanan

**ID Pesanan:** #{{ $pemesanan->id }}

| Detail | Keterangan |
|--------|-----------|
| Nama Acara | {{ $pemesanan->nama_acara }} |
| Paket | {{ $pemesanan->paket->nama_paket ?? '-' }} |
| Tanggal Acara | {{ $pemesanan->tanggal_mulai->format('d M Y') }} - {{ $pemesanan->tanggal_selesai->format('d M Y') }} |
| Alamat Acara | {{ $pemesanan->alamat_acara }} |
| Total Harga | **Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}** |
| Status | Pending (Menunggu Pembayaran) |

---

## Langkah Selanjutnya

1. **Kirim Bukti Pembayaran** ke admin melalui WhatsApp: **087787387484**
2. Sertakan **ID Pesanan (#{{ $pemesanan->id }})** dalam pesan Anda
3. Admin akan memverifikasi pembayaran dan mengupdate status pesanan
4. Anda dapat melacak pesanan dengan **ID** ini kapan saja

---

## Melacak Pesanan

Jika Anda ingin melihat status pesanan, Anda dapat:

1. Kunjungi halaman tracking pesanan
2. Masukkan **ID Pesanan: {{ $pemesanan->id }}**
3. Gunakan email: **{{ $pemesanan->guest_email ?? $pemesanan->user->email }}**
4. Anda akan menerima kode OTP untuk verifikasi

---

Jika ada pertanyaan, silakan hubungi admin melalui WhatsApp atau email.

@component('mail::button', ['url' => url('/tracking-guest')])
Lihat Status Pesanan
@endcomponent

Terima kasih,
**Tim Sonsun Event Organizer**
@endcomponent
