<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\Produk;

class WarehouseStockController extends Controller
{
    // Tampilkan daftar stok gudang aktif
    public function index()
    {
        $warehouseId = session('active_warehouse_id');

        if (!$warehouseId) {
            return redirect()->route('warehouse.select')->with('error', 'Silakan pilih gudang terlebih dahulu.');
        }

        $stocks = WarehouseStock::with('produk')
            ->where('warehouse_id', $warehouseId)
            ->get();

        return view('pages.warehouse_stocks.index', compact('stocks'));
    }

    // Form tambah stok
    public function create()
    {
        $warehouseId = session('active_warehouse');
        if (!$warehouseId) {
            return redirect()->route('warehouse.select')->with('error', 'Pilih gudang dulu.');
        }

        $produks = Produk::all();

        return view('pages.warehouse_stocks.create', compact('produks'));
    }

    // Simpan stok baru
    public function store(Request $request)
    {
        $warehouseId = session('active_warehouse');
        if (!$warehouseId) {
            return redirect()->route('warehouse.select')->with('error', 'Pilih gudang dulu.');
        }

        $request->validate([
            'barang_id' => 'required|exists:produk,barang_id',
            'stock_quantity' => 'required|integer|min:1',
        ]);

        WarehouseStock::create([
            'warehouse_id'   => $warehouseId,
            'barang_id'      => $request->barang_id,
            'stock_quantity' => $request->stock_quantity,
        ]);

        return redirect()->route('warehouseStocks.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    // Edit stok
    public function edit($id)
    {
        $stock = WarehouseStock::findOrFail($id);
        return view('pages.warehouse_stocks.edit', compact('stock'));
    }

    // Update stok
    public function update(Request $request, $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0', // ganti jumlah -> stock_quantity
        ]);

        $stock = WarehouseStock::findOrFail($id);
        $stock->update([
            'stock_quantity' => $request->stock_quantity, // ganti jumlah -> stock_quantity
        ]);

        return redirect()->route('warehouseStocks.index')->with('success', 'Stok berhasil diperbarui.');
    }

    // Hapus stok
    public function destroy($id)
    {
        $stock = WarehouseStock::findOrFail($id);
        $stock->delete();

        return redirect()->route('warehouseStocks.index')->with('success', 'Stok berhasil dihapus.');
    }
}
