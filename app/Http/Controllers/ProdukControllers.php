<?php

namespace App\Http\Controllers;

use Illuminate\Https\Request;
use App\Models\produk;
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
        $data = produk::get();
        return view('pages.produk.show', [
            'data_toko' => $data_toko,
            'data_produk' => $data
        ]);
    }
}
