<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        $query = Barang::query();

        // Search
        if (request('search')) {
            $query->where(function($q) {
                $q->where('nama_barang', 'ilike', '%' . request('search') . '%')
                  ->orWhere('deskripsi', 'ilike', '%' . request('search') . '%');
            });
        }

        // Filter stok
        if (request('stok_status') === 'habis') {
            $query->where('stok', '<=', 0);
        } elseif (request('stok_status') === 'tersedia') {
            $query->where('stok', '>', 0);
        }

        $barangs = $query->orderBy('updated_at', 'desc')->get();
        return view('admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $validated['foto'] = 'uploads/barang/' . $filename;
        }

        Barang::create($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $barang = Barang::with('pakets')->findOrFail($id);
        return view('admin.barang.show', compact('barang'));
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);
        
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $validated['foto'] = 'uploads/barang/' . $filename;
        }

        $barang->update($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
