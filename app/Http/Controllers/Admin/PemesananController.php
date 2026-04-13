<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PemesananController extends Controller
{
    public function index()
    {
        $query = Pemesanan::with(['user', 'paket']);

        // Search
        if (request('search')) {
            $query->where(function($q) {
                $q->where('nama_acara', 'ilike', '%' . request('search') . '%')
                  ->orWhereHas('user', function($qu) {
                      $qu->where('name', 'ilike', '%' . request('search') . '%');
                  });
            });
        }

        // Filter status pembayaran
        if (request('status_pembayaran')) {
            $query->where('status_pembayaran', request('status_pembayaran'));
        }

        // Filter status pengambilan
        if (request('status_pengambilan')) {
            $query->where('status_pengambilan', request('status_pengambilan'));
        }

        // Filter tanggal
        if (request('tanggal_dari')) {
            $query->where('tanggal_mulai', '>=', request('tanggal_dari'));
        }
        if (request('tanggal_sampai')) {
            $query->where('tanggal_mulai', '<=', request('tanggal_sampai'));
        }

        $pemesanans = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.pemesanan.index', compact('pemesanans'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with(['user', 'paket'])->findOrFail($id);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function validasiPembayaran(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        $pemesanan->update([
            'status_pembayaran' => 'lunas',
        ]);

        return back()->with('success', 'Pembayaran berhasil divalidasi');
    }

    public function updateStatus(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        $validated = $request->validate([
            'status_pengambilan' => 'required|in:belum_diambil,dalam_penggunaan,selesai',
        ]);

        $pemesanan->update($validated);

        return back()->with('success', 'Status pemesanan berhasil diupdate');
    }
}
