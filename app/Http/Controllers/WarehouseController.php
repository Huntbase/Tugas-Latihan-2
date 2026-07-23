<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Daftar warehouse_id yang boleh diakses user yang login.
     * Admin (role_id 1) tidak dibatasi - null berarti "semua boleh".
     * Selain Admin, dibatasi cuma yang ada di user_warehouse_assignments.
     */
    private function accessibleWarehouseIds()
    {
        $user = Auth::user();

        if ($user->role_id === 1) {
            return null; // null = tidak difilter, akses semua
        }

        return $user->warehouseAssignments()->pluck('warehouse_id')->toArray();
    }

    private function assertCanAccessWarehouse(int $warehouseId): void
    {
        $allowed = $this->accessibleWarehouseIds();

        // null berarti Admin, selalu boleh
        if ($allowed === null) {
            return;
        }

        abort_unless(in_array($warehouseId, $allowed), 403, 'Anda tidak memiliki akses ke warehouse ini.');
    }

    public function select()
    {
        $allowed = $this->accessibleWarehouseIds();

        $warehouses = Warehouse::when($allowed !== null, function ($query) use ($allowed) {
            $query->whereIn('warehouse_id', $allowed);
        })
            ->get();

        return view('pages.warehouse.select', compact('warehouses'));
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);

        // Cegah user set active warehouse ke gudang yang bukan haknya,
        // walau dia coba kirim warehouse_id lain lewat manipulasi form/POST.
        $this->assertCanAccessWarehouse((int) $request->warehouse_id);

        session(['active_warehouse_id' => $request->warehouse_id]);

        return redirect()->route('dashboard')->with('success', 'Gudang aktif disimpan.');
    }

    // Dashboard khusus warehouse aktif
    public function dashboard()
    {
        $warehouseId = session('active_warehouse_id');

        if (!$warehouseId) {
            return redirect()->route('warehouse.select')
                ->with('error', 'Silakan pilih warehouse dulu.');
        }

        // Jaga-jaga: kalau assignment user berubah setelah dia pilih
        // warehouse aktif (misal di-unassign Admin), gudang di session
        // jadi tidak valid lagi - paksa pilih ulang.
        $allowed = $this->accessibleWarehouseIds();
        if ($allowed !== null && !in_array((int) $warehouseId, $allowed)) {
            session()->forget('active_warehouse_id');
            return redirect()->route('warehouse.select')
                ->with('error', 'Akses ke warehouse sebelumnya sudah tidak berlaku. Silakan pilih ulang.');
        }

        // Dropdown ganti gudang - cuma tampilkan yang dia berhak akses
        $warehouses = Warehouse::when($allowed !== null, function ($query) use ($allowed) {
            $query->whereIn('warehouse_id', $allowed);
        })
            ->get();

        $warehouse = Warehouse::with('stocks.produk')->findOrFail($warehouseId);

        $totalBarang = $warehouse->stocks()->count();
        $stokBanyak = $warehouse->stocks()->where('stock_quantity', '>', 50)->count();
        $stokHampirHabis = $warehouse->stocks()->whereBetween('stock_quantity', [1, 10])->count();
        $stokKosong = $warehouse->stocks()->where('stock_quantity', 0)->count();

        return view('pages.warehouse.dashboard', compact(
            'warehouses',
            'warehouse',
            'totalBarang',
            'stokBanyak',
            'stokHampirHabis',
            'stokKosong'
        ));
    }

    public function index(Request $request)
    {
        $search = $request->keyword;
        $allowed = $this->accessibleWarehouseIds();

        $warehouses = Warehouse::when($allowed !== null, function ($query) use ($allowed) {
            $query->whereIn('warehouse_id', $allowed);
        })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('pages.warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        // Membuat warehouse baru = perubahan struktural, tetap domain Admin
        // (sesuai matriks akses: Admin akses global CRUD).
        abort_unless(Auth::user()->role_id === 1, 403);

        return view('pages.warehouse.addWarehouse');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role_id === 1, 403);

        $request->validate([
            'name_id' => 'required',
            'location_id' => 'required',
            'description' => 'nullable|string',
        ], [
            'name_id.required' => 'Nama Gudang wajib diisi!',
            'location_id.required' => 'Lokasi wajib diisi!',
            'description.required' => 'Deskripsi wajib diisi!',
        ]);

        Warehouse::create([
            'name_id'     => $request->name_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
        ]);

        return redirect('/warehouse')->with('pesan', 'berhasil menambahkan data');
    }

    public function show($id)
    {
        $this->assertCanAccessWarehouse((int) $id);

        $warehouse = Warehouse::with(['stocks.produk'])->findOrFail($id);

        return view('pages.warehouse.dashboard', compact('warehouse'));
    }

    public function edit(string $id)
    {
        // Ganti nama/lokasi gudang = perubahan struktural, tetap domain Admin.
        abort_unless(Auth::user()->role_id === 1, 403);

        $data = Warehouse::findOrFail($id);

        return view('pages.warehouse.edit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request, string $id)
    {
        abort_unless(Auth::user()->role_id === 1, 403);

        $request->validate([
            'name_id' => 'required',
            'location_id' => 'required',
            'description' => 'nullable|string',
        ], [
            'name_id.required' => 'Nama Gudang wajib diisi!',
            'location_id.required' => 'Lokasi wajib diisi!',
            'description.required' => 'Deskripsi wajib diisi!',
        ]);

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->name_id       = $request->name_id;
        $warehouse->location_id   = $request->location_id;
        $warehouse->description   = $request->description;
        $warehouse->save();

        return redirect('/warehouse')->with('pesan', 'berhasil Mengupdate data');
    }

    public function destroy(string $id)
    {
        abort_unless(Auth::user()->role_id === 1, 403);

        Warehouse::findOrFail($id)->delete();
        return redirect('/warehouse')->with('pesan', 'data berhasil di hapus');
    }
}
