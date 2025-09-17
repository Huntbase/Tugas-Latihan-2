<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeWarehouseId = session('active_warehouse_id');

        // Ambil semua gudang buat dropdown
        $warehouses = Warehouse::all();

        // Ambil gudang aktif
        $warehouse = null;
        if ($activeWarehouseId) {
            $warehouse = Warehouse::with('stocks.produk')->find($activeWarehouseId);
        }

        // Hitung statistik stok
        $totalBarang = $warehouse?->stocks()->count() ?? 0;
        $stokBanyak = $warehouse?->stocks()->where('stock_quantity', '>', 50)->count() ?? 0;
        $stokHampirHabis = $warehouse?->stocks()->whereBetween('stock_quantity', [1, 10])->count() ?? 0;
        $stokKosong = $warehouse?->stocks()->where('stock_quantity', 0)->count() ?? 0;

        return view('pages.warehouse.dashboard', compact(
            'warehouses',
            'warehouse',
            'totalBarang',
            'stokBanyak',
            'stokHampirHabis',
            'stokKosong'
        ));
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);

        session(['active_warehouse_id' => $request->warehouse_id]);

        return redirect()->route('dashboard.index');
    }
}
