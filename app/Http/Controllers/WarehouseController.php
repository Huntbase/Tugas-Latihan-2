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

        // simpan pilihan warehouse di session
        session(['active_warehouse_id' => $request->warehouse_id]);

        return redirect()->route('warehouse.dashboard');
    }

    // Dashboard khusus warehouse aktif
    public function dashboard()
    {
        $warehouseId = session('active_warehouse_id');

        if (!$warehouseId) {
            return redirect()->route('warehouse.select')->with('error', 'Silakan pilih warehouse dulu.');
        }

        $warehouse = Warehouse::with('stocks.product')->findOrFail($warehouseId);

        return view('pages.warehouse.dashboard', compact('warehouse'));
    }
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
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
    public function show(string $id)
    {
        // perintah untuk mengambil data 
        $data = Warehouse::findOrFail($id);

        return view('pages.warehouse.detail', [
            'warehouse' => $data,
        ]);
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
