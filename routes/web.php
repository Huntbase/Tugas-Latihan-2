<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseStockController;
use App\Http\Controllers\StockTransferController;
use App\Http\Middleware\VerifyRoleId;
use App\Http\Controllers\WarehouseAssignmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route login - TIDAK butuh auth (justru ini untuk login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root ke dashboard (user harus login)
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Semua route di bawah ini WAJIB login. Ini penting karena controller
| seperti WarehouseController/WarehouseStockController/dst memanggil
| Auth::user()->role_id secara langsung. Kalau route ini diakses tanpa
| login, Auth::user() balikin null dan aplikasi crash dengan error
| "Attempt to read property on null".
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard (tetap butuh warehouse.selected juga)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Warehouse
    Route::get('/warehouse/select', [WarehouseController::class, 'select'])->name('warehouse.select');
    Route::post('/warehouse/set-active', [WarehouseController::class, 'setActive'])->name('warehouse.setActive');
    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
    Route::resource('warehouses', WarehouseController::class);
    Route::get('/warehouse/dashboard', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');

    // WarehouseStock
    Route::middleware(['warehouse.selected'])->group(function () {
        Route::resource('warehouseStocks', WarehouseStockController::class);
    });
    // Stock Transfer
    Route::get('/stock-transfers', [StockTransferController::class, 'index'])->name('stock-transfers.index');
    Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
    Route::post('/stock-transfers', [StockTransferController::class, 'store'])->name('stock-transfers.store');
    Route::post('/stock-transfers/{stockTransfer}/submit', [StockTransferController::class, 'submit'])->name('stock-transfers.submit');
    Route::post('/stock-transfers/{stockTransfer}/approve', [StockTransferController::class, 'approve'])->name('stock-transfers.approve');
    Route::post('/stock-transfers/{stockTransfer}/reject', [StockTransferController::class, 'reject'])->name('stock-transfers.reject');
    Route::post('/stock-transfers/{stockTransfer}/ship', [StockTransferController::class, 'ship'])->name('stock-transfers.ship');
    Route::post('/stock-transfers/{stockTransfer}/receive', [StockTransferController::class, 'receive'])->name('stock-transfers.receive');
    Route::post('/stock-transfers/{stockTransfer}/reject-delivery', [StockTransferController::class, 'rejectDelivery'])->name('stock-transfers.reject-delivery');

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

    // Admin: assign Supervisor/Staff ke warehouse
    Route::get('/warehouse-assignments', [WarehouseAssignmentController::class, 'index'])->name('warehouse-assignments.index');
    Route::post('/warehouse-assignments', [WarehouseAssignmentController::class, 'store'])->name('warehouse-assignments.store');
    Route::delete('/warehouse-assignments/{assignment}', [WarehouseAssignmentController::class, 'destroy'])->name('warehouse-assignments.destroy');
});
