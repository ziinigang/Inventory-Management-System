<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // LIST all inventory with stock levels
    public function index(Request $request)
    {
        $query = Inventory::with(['product.supplier'])
                          ->join('products', 'inventories.product_id', '=', 'products.id');

        // Search by product name or SKU
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', '%' . $request->search . '%')
                  ->orWhere('products.sku',  'like', '%' . $request->search . '%');
            });
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereRaw('inventories.quantity <= products.reorder_level');
            } elseif ($request->stock_status === 'out') {
                $query->where('inventories.quantity', '=', 0);
            } elseif ($request->stock_status === 'ok') {
                $query->whereRaw('inventories.quantity > products.reorder_level');
            }
        }

        $inventories = $query->select('inventories.*')
                             ->latest('inventories.updated_at')
                             ->paginate(10);

        // Summary stats for the top cards
        $stats = [
            'total'   => Inventory::count(),
            'low'     => Inventory::join('products', 'inventories.product_id',
                                         '=', 'products.id')
                                  ->whereRaw('inventories.quantity <= products.reorder_level')
                                  ->where('inventories.quantity', '>', 0)
                                  ->count(),
            'out'     => Inventory::where('quantity', 0)->count(),
            'healthy' => Inventory::join('products', 'inventories.product_id',
                                         '=', 'products.id')
                                  ->whereRaw('inventories.quantity > products.reorder_level')
                                  ->count(),
        ];

        return view('inventory.index', compact('inventories', 'stats'));
    }

    // SHOW single inventory record
    public function show(Inventory $inventory)
    {
        $inventory->load(['product.supplier', 'product.stockMovements.user']);
        $movements = $inventory->product
                               ->stockMovements()
                               ->with('user')
                               ->latest()
                               ->paginate(10);

        return view('inventory.show', compact('inventory', 'movements'));
    }

    // SHOW stock adjustment form
    public function edit(Inventory $inventory)
    {
        $inventory->load('product');
        return view('inventory.edit', compact('inventory'));
    }

    // UPDATE location only (quantity is updated via StockMovements)
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'location' => ['nullable', 'string', 'max:100'],
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Storage location updated.');
    }
}