<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-status',
                 [SupplierController::class, 'toggleStatus'])
         ->name('suppliers.toggle-status');

    // Inventory (only index, show, edit, update — no create/store/destroy)
    Route::resource('inventory', InventoryController::class)
         ->only(['index', 'show', 'edit', 'update']);

    // Stock Movements (only index, create, store, show — no edit/update/destroy)
    Route::resource('stock-movements', StockMovementController::class)
         ->only(['index', 'create', 'store', 'show']);
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});