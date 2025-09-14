<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WarehouseController;
use App\Http\Middleware\VerifyRoleId;
use App\Models\produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root ke dashboard (user harus login)
Route::get('/', function () {
    Auth::logout(); // hapus session login lama
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
});

// Dashboard (harus login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


// Warehouse
Route::get('/select-warehouse', [WarehouseController::class, 'select'])->name('warehouse.select');
Route::post('/set-warehouse', [WarehouseController::class, 'setActive'])->name('warehouse.setActive');
Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
Route::resource('warehouses', WarehouseController::class);
Route::get('/warehouse/dashboard', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');



// Produk
Route::get('/produk', [ProdukControllers::class, 'index'])->name('produk.index');
Route::get('/produk/create', [ProdukControllers::class, 'create'])->name('produk.create');
Route::post('/produk', [ProdukControllers::class, 'store'])->name('produk.store');
Route::get('/produk/{id}', [ProdukControllers::class, 'show'])->name('produk.show');
Route::get('/produk/{id}/edit', [ProdukControllers::class, 'edit'])->name('produk.edit');
Route::put('/produk/{id}', [ProdukControllers::class, 'update'])->name('produk.update');
Route::delete('/produk/{id}', [ProdukControllers::class, 'destroy'])->name('produk.destroy');

// Audit Log
Route::get('/auditLog', [AuditLogController::class, 'index'])->name('auditLog.index');


// Manage User & Role
Route::resource('Data_users', UserController::class)
    ->middleware([VerifyRoleId::class . ':1']);

Route::post('/Data_users/{id}/role', [UserController::class, 'updateRole'])
    ->name('Data_users.updateRole')
    ->middleware([VerifyRoleId::class . ':1']);
