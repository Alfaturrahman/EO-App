<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPesanan = Pemesanan::count();
        $pesananInProgress = Pemesanan::where('status_pengambilan', 'dalam_penggunaan')->count();
        $totalPemasukan = Pemesanan::where('status_pembayaran', 'lunas')->sum('total_harga');
        
        $recentPemesanans = Pemesanan::with(['user', 'paket'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data untuk grafik bulanan (12 bulan terakhir)
        $monthlyData = [];
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            
            $count = Pemesanan::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $revenue = Pemesanan::where('status_pembayaran', 'lunas')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_harga');
            
            $monthlyData[] = $count;
            $monthlyRevenue[] = $revenue;
        }

        // Data untuk grafik tahunan (5 tahun terakhir)
        $yearlyData = [];
        $yearlyRevenue = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            
            $count = Pemesanan::whereYear('created_at', $year)->count();
            $revenue = Pemesanan::where('status_pembayaran', 'lunas')
                ->whereYear('created_at', $year)
                ->sum('total_harga');
            
            $yearlyData[] = $count;
            $yearlyRevenue[] = $revenue;
        }

        return view('admin.dashboard', compact(
            'totalPesanan',
            'pesananInProgress',
            'totalPemasukan',
            'recentPemesanans',
            'monthlyData',
            'monthlyRevenue',
            'yearlyData',
            'yearlyRevenue'
        ));
    }
}
