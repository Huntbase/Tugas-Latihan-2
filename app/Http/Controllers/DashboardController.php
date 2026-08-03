<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Role IDs sesuai tabel roles: 1=Admin, 2=Supervisor, 3=Staff
    private const ADMIN = 1;
    private const SUPERVISOR = 2;
    private const STAFF = 3;

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id === self::STAFF) {
            return $this->staffDashboard($user);
        }

        return $this->overviewDashboard($user);
    }

    /**
     * Dashboard untuk Admin & Supervisor - agregat lintas gudang,
     * TIDAK butuh pilih warehouse dulu. Admin lihat semua gudang,
     * Supervisor cuma lihat gudang yang dia awasi.
     */
    private function overviewDashboard($user)
    {
        $allowed = $user->role_id === self::ADMIN
            ? null // null = tidak difilter, Admin lihat semua
            : $user->warehouseAssignments()->pluck('warehouse_id');

        $stockQuery = WarehouseStock::when($allowed !== null, function ($q) use ($allowed) {
            $q->whereIn('warehouse_id', $allowed);
        });

        $totalBarang     = (clone $stockQuery)->count();
        $stokBanyak      = (clone $stockQuery)->where('stock_quantity', '>', 50)->count();
        $stokHampirHabis = (clone $stockQuery)->whereBetween('stock_quantity', [1, 10])->count();
        $stokKosong      = (clone $stockQuery)->where('stock_quantity', 0)->count();

        // Breakdown per gudang - buat tabel ringkas di bawah kartu statistik
        $warehouses = Warehouse::when($allowed !== null, function ($q) use ($allowed) {
            $q->whereIn('warehouse_id', $allowed);
        })
            ->withCount(['stocks as total_barang'])
            ->get();

        return view('pages.dashboard.dashboard', compact(
            'totalBarang',
            'stokBanyak',
            'stokHampirHabis',
            'stokKosong',
            'warehouses'
        ));
    }

    /**
     * Dashboard untuk Staff - actionable items saja, sesuai gudang yang
     * dia ditugaskan. Bukan statistik stok keseluruhan.
     */
    private function staffDashboard($user)
    {
        $warehouseIds = $user->warehouseAssignments()->pluck('warehouse_id');

        $needsToShip = StockTransfer::where('status', 'disetujui')
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->count();

        $needsToReceive = StockTransfer::where('status', 'dikirim')
            ->whereIn('to_warehouse_id', $warehouseIds)
            ->count();

        $shipList = StockTransfer::with(['barang', 'fromWarehouse', 'toWarehouse'])
            ->where('status', 'disetujui')
            ->whereIn('from_warehouse_id', $warehouseIds)
            ->latest()
            ->take(5)
            ->get();

        $receiveList = StockTransfer::with(['barang', 'fromWarehouse', 'toWarehouse'])
            ->where('status', 'dikirim')
            ->whereIn('to_warehouse_id', $warehouseIds)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.staff.staff', compact(
            'needsToShip',
            'needsToReceive',
            'shipList',
            'receiveList'
        ));
    }

    /**
     * NOTE: method ini kelihatannya duplikat dari WarehouseController::setActive().
     * Route yang aktif sekarang pakai warehouse.setActive (WarehouseController),
     * jadi method ini kemungkinan sudah tidak dipakai. Dibiarkan dulu, aman
     * dihapus kalau dikonfirmasi tidak ada yang memanggil route ini.
     */
    public function setActive(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);

        session(['active_warehouse_id' => $request->warehouse_id]);

        return redirect()->route('dashboard');
    }
}
