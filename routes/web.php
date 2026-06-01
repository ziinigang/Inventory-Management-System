<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;

// yung mga page na pwedeng buksan kahit hindi pa naka-login(public pages)
// yung login page mismo ang nasa loob nito
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// yung mga page na kailangan naka-login muna bago ma-access(protected pages)
// pag hindi naka-login, ire-redirect ka sa login page
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    // lahat ng CRUD routes para sa management ng mga products
    Route::resource('products', ProductController::class);

    // Suppliers
    // lahat ng CRUD routes para sa pamamahala ng mga suppliers
    Route::resource('suppliers', SupplierController::class);
    // dagdag na route para sa pag-activate o deactivate ng supplier
    Route::patch('suppliers/{supplier}/toggle-status',
                 [SupplierController::class, 'toggleStatus'])
         ->name('suppliers.toggle-status');

         // routes para sa inventory
    // index=listahan, show=tingnan, edit=baguhin lokasyon, update=i-save
    // walang create at delete kasi yung inventory record ay
    // awtomatikong ginagawa pag nagdagdag ng bagong product

    // Inventory (only index, show, edit, update — no create/store/destroy)
    Route::resource('inventory', InventoryController::class)
         ->only(['index', 'show', 'edit', 'update']);

        // routes para sa stock movements tas and index=listahan, create=form, store=i-save, show=tingnan yung detalye
        // walang edit at delete kasi permanent yung bawat movement
        // para sa audit trail, hindi pwedeng burahin o baguhin yung nakaraang movements
         // Stock Movements (only index, create, store, show — no edit/update/destroy)
    Route::resource('stock-movements', StockMovementController::class)
         ->only(['index', 'create', 'store', 'show']);
});

// pag nag-open ng website without specific page, diretso sa dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});