<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProdukControllers;
use App\Models\produk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/produk', [ProdukControllers::class, 'index']); // read data menampilkan data

Route::get('/produk/create', [ProdukControllers::class, 'create']); // menampilkan halaman form data
Route::post('/produk', [ProdukControllers::class, 'store']); // untuk mengelola data yang telah dikriim dari halaman form data

Route::get('/produk/{id}', [ProdukControllers::class, 'show']); // untuk menampilkan halaman detail data

Route::get('/produk/{id}/edit', [ProdukControllers::class, 'edit']);
Route::put('/produk/{id}', [ProdukControllers::class, 'update']);

Route::delete('/produk/{id}', [ProdukControllers::class, 'destroy']);

Route::get('/auditLog', [AuditLogController::class, 'index'])->name('auditLog.index');


Route::get('/login', function () {
    return view('pages.login');
});
