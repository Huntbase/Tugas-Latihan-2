<?php

use App\Http\Controllers\ProdukControllers;
use App\Models\produk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/produk', [ProdukControllers::class, 'index']); // read data menampilkan data

Route::get('/produk/create', [ProdukControllers::class, 'create']); // menampilkan halaman form data
Route::post('/produk', [ProdukControllers::class, 'store']); // untuk mengelola data yang telah dikriim dari halaman form data

Route::get('/produk/{id}', [ProdukControllers::class, 'show']);
Route::get('/auditLog', function () {
    return view('pages.auditLog');
});

Route::get('/login', function () {
    return view('pages.login');
});
