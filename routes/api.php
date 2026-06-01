<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\SupplierApiController;
use App\Http\Controllers\Api\InventoryApiController;
use App\Http\Controllers\Api\StockMovementApiController;

// ─── Public routes (no token needed) ────────────────────────────────────────
Route::post('/login',  [AuthApiController::class, 'login']);

// ─── Protected routes (Bearer token required) ────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me',      [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Products — full CRUD
    Route::apiResource('products', ProductApiController::class);

    // Suppliers — full CRUD
    Route::apiResource('suppliers', SupplierApiController::class);

    // Inventories — no create/delete (managed via products + movements)
    Route::apiResource('inventories', InventoryApiController::class)
         ->only(['index', 'show', 'update']);

    // Stock Movements — no edit/update/delete (immutable audit trail)
    Route::apiResource('stock-movements', StockMovementApiController::class)
         ->only(['index', 'store', 'show']);
});