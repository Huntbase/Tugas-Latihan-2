<?php

use App\Http\Controllers\ProdukControllers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/produk', [ProdukControllers::class, 'index']);

Route::get('/auditLog', function () {
    return view('pages.auditLog');
});

Route::get('/login', function () {
    return view('pages.login');
});
