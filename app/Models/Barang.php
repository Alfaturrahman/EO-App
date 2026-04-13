<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'harga_sewa',
        'stok',
        'foto',
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
    ];

    public function pakets()
    {
        return $this->belongsToMany(Paket::class, 'barang_paket')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
