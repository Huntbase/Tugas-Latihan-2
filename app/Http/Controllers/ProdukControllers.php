<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdukControllers extends Controller
{
    // Role IDs sesuai tabel roles: 1=Admin, 2=Supervisor, 3=Staff
    // Edit produk boleh Admin & Supervisor. Staff read-only sesuai matriks akses.
    private const CAN_EDIT_PRODUK = [1, 2];

    public function index(Request $request)
    {
        $search = $request->keyword;
        $category = $request->category;

        $data = Produk::when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->orderBy('nama_barang')
            ->paginate(15)
            ->withQueryString();

        // Daftar kategori unik yang ada di data, buat dropdown filter
        $categories = Produk::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.produk.show', [
            'data_produk' => $data,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_EDIT_PRODUK), 403, 'Staff tidak memiliki akses untuk menambah produk.');

        return view('pages.produk.addProduk');
    }

    public function store(Request $request)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_EDIT_PRODUK), 403, 'Staff tidak memiliki akses untuk menambah produk.');

        $request->validate([
            'nama_barang' => 'required',
            'category' => 'required',
            'unit' => 'required|numeric|min:1',
        ], [
            'nama_barang.required' => 'Nama Barang wajib diisi!',
            'category.required' => 'Categori wajib diisi!',
            'unit.required' => 'Banyaknya unit wajib diisi!',
            'unit.numeric' => 'Unit harus berupa angka!',
            'unit.min' => 'Minimal unit adalah 1!',
        ]);

        Produk::create([
            'nama_barang' => $request->nama_barang,
            'category' => $request->category,
            'unit' => $request->unit,
        ]);

        return redirect('/produk')->with('pesan', 'berhasil menambahkan data');
    }

    public function show($id)
    {
        // Read-only, terbuka untuk semua role termasuk Staff
        $data = Produk::findOrFail($id);

        return view('pages.produk.detail', [
            'produk' => $data,
        ]);
    }

    public function edit($id)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_EDIT_PRODUK), 403, 'Staff tidak memiliki akses untuk mengubah produk.');

        $data = Produk::findOrFail($id);

        return view('pages.produk.edit', [
            'data' => $data,
        ]);
    }

    public function update($id, Request $request)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_EDIT_PRODUK), 403, 'Staff tidak memiliki akses untuk mengubah produk.');

        $request->validate([
            'nama_barang' => 'required',
            'category' => 'required',
            'unit' => 'required|numeric|min:1',
        ], [
            'nama_barang.required' => 'Nama Barang wajib diisi!',
            'category.required' => 'Categori wajib diisi!',
            'unit.required' => 'Banyaknya unit wajib diisi!',
            'unit.numeric' => 'Unit harus berupa angka!',
            'unit.min' => 'Minimal unit adalah 1!',
        ]);

        $produk = Produk::findOrFail($id);

        $produk->nama_barang = $request->nama_barang;
        $produk->category   = $request->category;
        $produk->unit       = $request->unit;

        $produk->save();

        return redirect('/produk')->with('pesan', 'berhasil Mengupdate data');
    }

    public function destroy($id)
    {
        abort_unless(in_array(Auth::user()->role_id, self::CAN_EDIT_PRODUK), 403, 'Staff tidak memiliki akses untuk menghapus produk.');

        Produk::findOrFail($id)->delete();
        return redirect('/produk')->with('pesan', 'data berhasil di hapus');
    }
}