<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;

class HomeController extends Controller
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

        // Sort
        $sortBy = request('sort', 'created_at');
        $sortOrder = request('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pakets = $query->get();
        return view('user.home', compact('pakets'));
    }

    public function show($id)
    {
        $paket = Paket::with('barangs')->findOrFail($id);
        return view('user.paket-detail', compact('paket'));
    }
}
