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

        // Balik ke halaman yang minta (dikirim lewat hidden field
        // redirect_to), supaya switcher di halaman manapun tetap di
        // halaman itu juga - bukan selalu lompat ke satu tempat tetap.
        // Dibatasi ke daftar route yang diizinkan supaya aman dari
        // manipulasi nama route sembarangan.
        $allowedRedirects = ['warehouse.dashboard', 'warehouseStocks.index'];
        $redirectTo = $request->input('redirect_to', 'warehouse.dashboard');

        if (!in_array($redirectTo, $allowedRedirects)) {
            $redirectTo = 'warehouse.dashboard';
        }

        return redirect()->route($redirectTo)->with('success', 'Gudang aktif disimpan.');
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

        return view('pages.warehouse.warehouse', compact(
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
            'name' => 'required',
            'location' => 'required',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Gudang wajib diisi!',
            'location.required' => 'Lokasi wajib diisi!',
            'description.required' => 'Deskripsi wajib diisi!',
        ]);

        Warehouse::create([
            'name'     => $request->name,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect('/warehouse')->with('pesan', 'berhasil menambahkan data');
    }

    public function show($id)
    {
        $this->assertCanAccessWarehouse((int) $id);

        $allowed = $this->accessibleWarehouseIds();

        // Dropdown switcher - sama seperti di dashboard()
        $warehouses = Warehouse::when($allowed !== null, function ($query) use ($allowed) {
            $query->whereIn('warehouse_id', $allowed);
        })
            ->get();

        $warehouse = Warehouse::with(['stocks.produk'])->findOrFail($id);

        $totalBarang = $warehouse->stocks()->count();
        $stokBanyak = $warehouse->stocks()->where('stock_quantity', '>', 50)->count();
        $stokHampirHabis = $warehouse->stocks()->whereBetween('stock_quantity', [1, 10])->count();
        $stokKosong = $warehouse->stocks()->where('stock_quantity', 0)->count();

        return view('pages.warehouse.warehouse', compact(
            'warehouses',
            'warehouse',
            'totalBarang',
            'stokBanyak',
            'stokHampirHabis',
            'stokKosong'
        ));
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
            'name' => 'required',
            'location' => 'required',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Gudang wajib diisi!',
            'location.required' => 'Lokasi wajib diisi!',
            'description.required' => 'Deskripsi wajib diisi!',
        ]);

        $warehouse = Warehouse::findOrFail($id);

        $warehouse->name       = $request->name;
        $warehouse->location   = $request->location;
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
