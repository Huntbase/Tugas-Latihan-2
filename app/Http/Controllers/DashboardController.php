<?php

namespace App\Http\Controllers;

use App\Models\Produk; // sesuaikan dengan model kamu
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total jenis barang
        $totalBarang = Produk::count();

        // Barang masih banyak (contoh stok > 50 unit)
        $stokBanyak = Produk::where('unit', '>', 50)->count();

        // Barang hampir habis (stok antara 1 s/d 10 unit)
        $stokHampirHabis = Produk::whereBetween('unit', [1, 10])->count();

        // Barang kosong (stok 0)
        $stokKosong = Produk::where('unit', 0)->count();

        return view('pages.dashboard.dashboard', compact(
            'totalBarang',
            'stokBanyak',
            'stokHampirHabis',
            'stokKosong'
        ));
    }
}
