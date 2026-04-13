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
        $rules = [
            'paket_id' => 'required|exists:pakets,id',
            'nama_acara' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_acara' => 'required|string',
        ];

        // If guest (not logged in), require guest info
        if (!Auth::check()) {
            $rules['guest_nama'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $paket = Paket::findOrFail($validated['paket_id']);
        
        $pemesananData = [
            'paket_id' => $validated['paket_id'],
            'nama_acara' => $validated['nama_acara'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alamat_acara' => $validated['alamat_acara'],
            'total_harga' => $paket->harga_total,
            'status_pembayaran' => 'pending',
            'status_pengambilan' => 'belum_diambil',
        ];

        // Set user_id if logged in, otherwise set guest info
        if (Auth::check()) {
            $pemesananData['user_id'] = Auth::id();
        } else {
            $pemesananData['guest_nama'] = $validated['guest_nama'];
            $pemesananData['guest_email'] = $validated['guest_email'];
            $pemesananData['guest_phone'] = $validated['guest_phone'];
        }

        $pemesanan = Pemesanan::create($pemesananData);

        // Redirect with order ID for guest users
        return redirect()->route('pemesanan.success', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan simpan ID pesanan Anda: #' . $pemesanan->id);
    }

    public function success($id)
    {
        $pemesanan = Pemesanan::with('paket')->findOrFail($id);
        return view('user.pemesanan.success', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('paket')->findOrFail($id);
        
        // Check access: either owns the order or is guest order (no user_id)
        if ($pemesanan->user_id && $pemesanan->user_id !== Auth::id()) {
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
