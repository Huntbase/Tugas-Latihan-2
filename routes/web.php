<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukControllers;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Login (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout (hanya untuk user yang login)
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// Semua route setelah login
Route::middleware('auth')->group(function () {

    // Dashboard untuk semua role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/manage-users', function () {
            return "Halaman Manage User & Role";
        })->name('manage-users');
    });

    // Admin + Supervisor
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::resource('produk', ProdukControllers::class);
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log');
    });

    // Admin + Supervisor + Staff
    Route::middleware('role:admin,supervisor,staff')->group(function () {
        Route::get('/manage-stock', function () {
            return "Halaman Manage Stok";
        })->name('manage-stock');
    });
});
