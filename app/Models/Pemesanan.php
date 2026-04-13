<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $fillable = [
        'user_id',
        'paket_id',
        'nama_acara',
        'tanggal_mulai',
        'tanggal_selesai',
        'alamat_acara',
        'total_harga',
        'status_pembayaran',
        'status_pengambilan',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
}
