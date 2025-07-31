<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/product', function () {
    return view('pages.product');
});

Route::get('/auditLog', function () {
    return view('pages.auditLog');
});

Route::get('/login', function () {
    return view('pages.login');
});
