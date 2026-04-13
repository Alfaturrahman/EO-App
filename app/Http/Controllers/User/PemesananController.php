<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
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
        $validated = $request->validate([
            'paket_id' => 'required|exists:pakets,id',
            'nama_acara' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_acara' => 'required|string',
        ]);

        $paket = Paket::findOrFail($validated['paket_id']);
        
        $pemesanan = Pemesanan::create([
            'user_id' => Auth::id(),
            'paket_id' => $validated['paket_id'],
            'nama_acara' => $validated['nama_acara'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alamat_acara' => $validated['alamat_acara'],
            'total_harga' => $paket->harga_total,
            'status_pembayaran' => 'pending',
            'status_pengambilan' => 'belum_diambil',
        ]);

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil dibuat. Silakan upload bukti pembayaran.');
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('paket')->findOrFail($id);
        
        if ($pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.pemesanan.show', compact('pemesanan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        if ($pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);
        
        if ($request->hasFile('bukti_pembayaran')) {
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
