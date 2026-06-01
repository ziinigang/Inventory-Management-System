<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\Inventory;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat cards ─────────────────────────────────────────
        $totalProducts  = Product::count();
        $totalSuppliers = Supplier::where('status', 'active')->count();
        $totalMovements = StockMovement::count();

        $lowStockCount  = Inventory::join('products',
                              'inventories.product_id', '=', 'products.id')
                              ->whereRaw('inventories.quantity
                                         <= products.reorder_level')
                              ->count();

        // ── Low stock items for the alert table ─────────────────
        $lowStockItems  = Inventory::with('product')
                              ->join('products',
                                     'inventories.product_id', '=', 'products.id')
                              ->whereRaw('inventories.quantity
                                         <= products.reorder_level')
                              ->select('inventories.*')
                              ->orderBy('inventories.quantity')
                              ->take(6)
                              ->get();

        // ── Recent movements ────────────────────────────────────
        $recentMovements = StockMovement::with(['product', 'user'])
                               ->latest()
                               ->take(6)
                               ->get();

        // ── Donut chart data ────────────────────────────────────
        $allInventory = Inventory::with('product')->get();

        $stockStats = [
            'in_stock'    => $allInventory->filter(
                                 fn($i) => $i->product
                                        && $i->quantity > $i->product->reorder_level
                             )->count(),
            'low_stock'   => $allInventory->filter(
                                 fn($i) => $i->product
                                        && $i->quantity > 0
                                        && $i->quantity <= $i->product->reorder_level
                             )->count(),
            'out_of_stock'=> $allInventory->filter(
                                 fn($i) => $i->quantity === 0
                             )->count(),
        ];

        // ── Bar chart: last 7 days movement totals ──────────────
        $chartData = $this->getChartData();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalMovements',
            'lowStockCount',
            'lowStockItems',
            'recentMovements',
            'stockStats',
            'chartData'
        ));
    }

    private function getChartData(): array
    {
        $labels = [];
        $in     = [];
        $out    = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::today()->subDays($i);
            $labels[] = $date->format('M d');

            $in[]  = StockMovement::whereDate('created_at', $date)
                                  ->where('type', 'in')
                                  ->sum('quantity');

            $out[] = StockMovement::whereDate('created_at', $date)
                                  ->where('type', 'out')
                                  ->sum('quantity');
        }

        return compact('labels', 'in', 'out');
    }
}