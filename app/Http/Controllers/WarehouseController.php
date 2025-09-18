<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function select()
    {
        $warehouses = Warehouse::all();
        return view('pages.warehouse.select', compact('warehouses'));
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,warehouse_id',
        ]);


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

        // Semua gudang buat dropdown
        $warehouses = Warehouse::all();

        // Ambil warehouse aktif + relasi produk
        $warehouse = Warehouse::with('stocks.produk')->findOrFail($warehouseId);

        // Hitung statistik
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

        $warehouses = Warehouse::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->paginate(10);

        return view('pages.warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.warehouse.addWarehouse');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'name_id' => 'required',
            'location_id' => 'required',
            'description' => 'nullable|string',
        ], [
            'name_id.required' => 'Nama Gudang wajib diisi!',
            'location_id.required' => 'Lokasi wajib diisi!',
            'description.required' => 'Deskripsi wajib diisi!',
        ]);

        // untuk menambah data ke tb_produk
        // query tambah data
        Warehouse::create([
            'name_id'     => $request->name_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
        ]);

        // setelah data berhasil di tambah, akan mengarahkan ke halaman /produk dan memberikan notif menambahkan data
        return redirect('/warehouse')->with('pesan', 'berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // perintah untuk mengambil data 
        $warehouse = Warehouse::with(['stocks.produk'])->findOrFail($id);

        return view('pages.warehouse.dashboard', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // mengambil 1 data spesifik id dari id yang dikirimkan yang spesifik
        $data = Warehouse::findOrFail($id);

        return view('pages.warehouse.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

        // Simpan → akan memicu trait Auditable
        $warehouse->save();

        return redirect('/warehouse')->with('pesan', 'berhasil Mengupdate data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // query untuk menghapus data di database
        Warehouse::findOrFail($id)->delete();
        return redirect('/warehouse')->with('pesan', 'data berhasil di hapus');
    }
}
