<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalSuppliers  = Supplier::where('status', 'active')->count();
        $lowStockCount   = Product::with('inventory')
                               ->get()
                               ->filter(fn($p) => $p->isLowStock())
                               ->count();
        $recentMovements = StockMovement::with(['product', 'user'])
                               ->latest()
                               ->take(5)
                               ->get();
        $totalMovements  = StockMovement::count();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'lowStockCount',
            'recentMovements',
            'totalMovements'
        ));
    }
}