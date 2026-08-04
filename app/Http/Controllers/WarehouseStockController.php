<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class WarehouseStockController extends Controller
{
    // Role IDs sesuai tabel roles: 1=Admin, 2=Supervisor, 3=Staff
    // Ubah stok manual (di luar alur transfer) cuma boleh Admin & Supervisor.
    // Staff cuma boleh ubah stok lewat alur draft->submit->approve->ship->receive,
    // bukan edit angka langsung - supaya semua perubahan stok Staff tetap
    // tercatat rapi di audit log stock transfer.
    private const CAN_MANAGE_STOCK = [1, 2];

    /**
     * Pastikan stok yang mau diedit/dihapus memang milik gudang yang
     * berhak diakses user ini. Mencegah Supervisor mengubah stok
     * gudang lain lewat tebak-tebakan ID di URL.
     */
    private function assertOwnsStock(WarehouseStock $stock): void
    {
        $user = Auth::user();

        if ($user->role_id === 1) {
            return; // Admin bebas akses
        }

        $allowed = $user->warehouseAssignments()->pluck('warehouse_id')->toArray();

        abort_unless(in_array($stock->warehouse_id, $allowed), 403, 'Anda tidak memiliki akses ke stok warehouse ini.');
    }

    // Tampilkan daftar stok gudang aktif - terbuka untuk semua role
    // (Staff tetap perlu lihat stok, cuma tidak boleh ubah manual).
    // NOTE: pengecekan "sudah pilih gudang atau belum" ditangani
    // middleware warehouse.selected di routes/web.php.
    public function index()
    {
        $warehouseId = session('active_warehouse_id');

        $stocks = WarehouseStock::with('produk')
            ->where('warehouse_id', $warehouseId)
            ->get();

        return view('pages.warehouse_stocks.index', compact('stocks'));
    }

    // Form tambah stok
    public function create()
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_MANAGE_STOCK), 403, 'Staff tidak memiliki akses untuk menambah stok secara manual.');

        $produks = Produk::all();

        return view('pages.warehouse_stocks.create', compact('produks'));
    }

    // Simpan stok baru
    public function store(Request $request)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_MANAGE_STOCK), 403, 'Staff tidak memiliki akses untuk menambah stok secara manual.');

        $warehouseId = session('active_warehouse_id');

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
        abort_unless(in_array(Auth::user()->role_id, self::CAN_MANAGE_STOCK), 403, 'Staff tidak memiliki akses untuk mengubah stok secara manual.');

        $stock = WarehouseStock::findOrFail($id);
        $this->assertOwnsStock($stock);

        return view('pages.warehouse_stocks.edit', compact('stock'));
    }

    // Update stok
    public function update(Request $request, $id)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_MANAGE_STOCK), 403, 'Staff tidak memiliki akses untuk mengubah stok secara manual.');

        $stock = WarehouseStock::findOrFail($id);
        $this->assertOwnsStock($stock);

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $stock->update([
            'stock_quantity' => $request->stock_quantity,
        ]);

        return redirect()->route('warehouseStocks.index')->with('success', 'Stok berhasil diperbarui.');
    }

    // Hapus stok
    public function destroy($id)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_MANAGE_STOCK), 403, 'Staff tidak memiliki akses untuk menghapus stok secara manual.');

        $stock = WarehouseStock::findOrFail($id);
        $this->assertOwnsStock($stock);

        $stock->delete();

        return redirect()->route('warehouseStocks.index')->with('success', 'Stok berhasil dihapus.');
    }
}
