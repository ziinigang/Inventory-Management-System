<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\Inventory;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts  = Product::count();
        $totalSuppliers = Supplier::where('status', 'active')->count();
        $totalMovements = StockMovement::count();

        $lowStockCount  = Inventory::join('products',
                                          'inventories.product_id', '=', 'products.id')
                                   ->whereRaw('inventories.quantity <= products.reorder_level')
                                   ->count();

        $recentMovements = StockMovement::with(['product', 'user'])
                                        ->latest()
                                        ->take(6)
                                        ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalMovements',
            'lowStockCount',
            'recentMovements'
        ));
    }
}