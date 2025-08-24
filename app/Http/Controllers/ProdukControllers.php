<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class ProdukControllers extends Controller
{
    public function index()
    {
        $data_toko = [
            'nama_toko' => 'Bibong Jaya Abadi',
            'alamat' => 'bibung jakarta kota',
            'type' => 'Ruko'
        ];
        $data = Produk::get();
        return view('pages.produk.show', [
            'data_toko' => $data_toko,
            'data_produk' => $data
        ]);
    }

    public function create()
    {
        return view('pages.produk.addProduk');
    }
    public function store(Request $request)
    {
        // validasi
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

        // untuk menambah data ke tb_produk
        // query tambah data
        Produk::create([
            'nama_barang' => $request->nama_barang,
            'category' => $request->category,
            'unit' => $request->unit,
        ]);

        // setelah data berhasil di tambah, akan mengarahkan ke halaman /produk dan memberikan notif menambahkan data
        return redirect('/produk')->with('pesan', 'berhasil menambahkan data');
    }

    public function show($id)
    {
        // perintah untuk mengambil data 
        $data = Produk::findOrFail($id);

        return view('pages.produk.detail', [
            'produk' => $data,
        ]);
    }

    public function edit($id)
    {
        // mengambil 1 data spesifik id dari id yang dikirimkan yang spesifik
        $data = Produk::findOrFail($id);

        return view('pages.produk.edit', [
            'data' => $data,
        ]);
    }

    public function update($id, Request $request)
    {
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

        // query untuk simpan data yang telah kita update
        produk::where('barang_id', $id)->update([
            'nama_barang' => $request->nama_barang,
            'category' => $request->category,
            'unit' => $request->unit,
        ]);
        return redirect('/produk')->with('pesan', 'berhasil Mengupdate data');
    }
}
