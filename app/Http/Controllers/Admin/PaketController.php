<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Barang;

class PaketController extends Controller
{
    public function index()
    {
        $query = Paket::query();

        // Search
        if (request('search')) {
            $query->where(function($q) {
                $q->where('nama_paket', 'ilike', '%' . request('search') . '%')
                  ->orWhere('deskripsi', 'ilike', '%' . request('search') . '%');
            });
        }

        // Filter harga
        if (request('harga_min')) {
            $query->where('harga_total', '>=', request('harga_min'));
        }
        if (request('harga_max')) {
            $query->where('harga_total', '<=', request('harga_max'));
        }

        $pakets = $query->orderBy('updated_at', 'desc')->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('admin.paket.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_total' => 'required|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
            'barangs' => 'nullable|array',
            'barangs.*' => 'exists:barangs,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/paket'), $filename);
            $validated['foto'] = 'uploads/paket/' . $filename;
        }

        $paket = Paket::create($validated);

        // Attach barang-barang ke paket
        if ($request->has('barangs')) {
            $syncData = [];
            foreach ($request->barangs as $index => $barangId) {
                $syncData[$barangId] = ['jumlah' => $request->jumlah[$index] ?? 1];
            }
            $paket->barangs()->sync($syncData);
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $paket = Paket::with('barangs')->findOrFail($id);
        return view('admin.paket.show', compact('paket'));
    }

    public function edit(string $id)
    {
        $paket = Paket::with('barangs')->findOrFail($id);
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('admin.paket.edit', compact('paket', 'barangs'));
    }

    public function update(Request $request, string $id)
    {
        $paket = Paket::findOrFail($id);
        
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_total' => 'required|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
            'barangs' => 'nullable|array',
            'barangs.*' => 'exists:barangs,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/paket'), $filename);
            $validated['foto'] = 'uploads/paket/' . $filename;
        }

        $paket->update($validated);

        // Update barang-barang
        if ($request->has('barangs')) {
            $syncData = [];
            foreach ($request->barangs as $index => $barangId) {
                $syncData[$barangId] = ['jumlah' => $request->jumlah[$index] ?? 1];
            }
            $paket->barangs()->sync($syncData);
        } else {
            $paket->barangs()->detach();
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dihapus');
    }
}
