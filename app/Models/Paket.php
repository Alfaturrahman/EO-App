<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_total',
        'foto',
    ];

    protected $casts = [
        'harga_total' => 'decimal:2',
    ];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_paket')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
