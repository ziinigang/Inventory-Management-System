<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ReportController;


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

    // lahat ng CRUD routes para sa management ng mga products
    Route::resource('products', ProductController::class);

    // Suppliers
    // lahat ng CRUD routes para sa pamamahala ng mga suppliers
    Route::resource('suppliers', SupplierController::class);
    // dagdag na route para sa pag-activate o deactivate ng supplier
    Route::patch('suppliers/{supplier}/toggle-status',
                 [SupplierController::class, 'toggleStatus'])
         ->name('suppliers.toggle-status');

         // routes for inventory at stock movements
    // Inventory (only index, show, edit, update — no create/store/destroy)
    Route::resource('inventory', InventoryController::class)
         ->only(['index', 'show', 'edit', 'update']);

       
         // Stock Movements (only index, create, store, show — no edit/update/destroy)
    Route::resource('stock-movements', StockMovementController::class)
         ->only(['index', 'create', 'store', 'show']);

    // Reports (admin only)
     Route::middleware(['auth', 'admin'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/',  [ReportController::class, 'index'])->name('index');

    // Inventory exports
    Route::get('/inventory/pdf',   [ReportController::class, 'inventoryPdf'])
         ->name('inventory.pdf');
    Route::get('/inventory/excel', [ReportController::class, 'inventoryExcel'])
         ->name('inventory.excel');
    Route::get('/inventory/csv',   [ReportController::class, 'inventoryCsv'])
         ->name('inventory.csv');

    // Stock movements exports
    Route::get('/movements/pdf',   [ReportController::class, 'movementsPdf'])
         ->name('movements.pdf');
    Route::get('/movements/excel', [ReportController::class, 'movementsExcel'])
         ->name('movements.excel');
    Route::get('/movements/csv',   [ReportController::class, 'movementsCsv'])
         ->name('movements.csv');

    // Suppliers exports
    Route::get('/suppliers/pdf',   [ReportController::class, 'suppliersPdf'])
         ->name('suppliers.pdf');
    Route::get('/suppliers/excel', [ReportController::class, 'suppliersExcel'])
         ->name('suppliers.excel');
    Route::get('/suppliers/csv',   [ReportController::class, 'suppliersCsv'])
         ->name('suppliers.csv');
});
});

// pag nag-open ng website without specific page, diretso sa dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});