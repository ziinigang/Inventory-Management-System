<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // dito makikita ang lahat ng produkto at kung ilan pa ang stock nila
    public function index(Request $request)
    {
        $query = Inventory::with(['product.supplier'])
                          ->join('products', 'inventories.product_id', '=', 'products.id');

        // kapag naghanap ang user, hahanapin natin sa pangalan o SKU ng produkto
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', '%' . $request->search . '%')
                  ->orWhere('products.sku', 'like', '%' . $request->search . '%');
            });
        }

        // kapag nag-filter ang user, ita-tago ang hindi relevant na items
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                // pababa na ang stock, kailangan na mag-reorder
                $query->whereRaw('inventories.quantity <= products.reorder_level');
            } elseif ($request->stock_status === 'out') {
                // wala nang stock, zero na talaga
                $query->where('inventories.quantity', '=', 0);
            } elseif ($request->stock_status === 'ok') {
                // sapat pa ang stock, hindi pa kailangang mag-alala
                $query->whereRaw('inventories.quantity > products.reorder_level');
            }
        }

        $inventories = $query->select('inventories.*')
                             ->latest('inventories.updated_at')
                             ->paginate(10);

        // mga bilang na ipapakita sa mga card sa taas ng page
        $stats = [
            'total'   => Inventory::count(),
            // ilan ang mga produktong mababa na ang stock pero hindi pa zero
            'low'     => Inventory::join('products', 'inventories.product_id', '=', 'products.id')
                                  ->whereRaw('inventories.quantity <= products.reorder_level')
                                  ->where('inventories.quantity', '>', 0)
                                  ->count(),
            // ilan ang mga produktong wala na talagang stock
            'out'     => Inventory::where('quantity', 0)->count(),
            // ilan ang mga produktong okay pa ang stock
            'healthy' => Inventory::join('products', 'inventories.product_id', '=', 'products.id')
                                  ->whereRaw('inventories.quantity > products.reorder_level')
                                  ->count(),
        ];

        return view('inventory.index', compact('inventories', 'stats'));
    }

    // dito makikita ang history ng isang produkto, lahat ng movements niya
    public function show(Inventory $inventory)
    {
        $inventory->load(['product.supplier', 'product.stockMovements.user']);
        // kunin ang lahat ng stock movements, pinaka-bago muna
        $movements = $inventory->product
                               ->stockMovements()
                               ->with('user')
                               ->latest()
                               ->paginate(10);

        return view('inventory.show', compact('inventory', 'movements'));
    }

    // dito pwedeng baguhin ang lokasyon ng produkto sa bodega
    public function edit(Inventory $inventory)
    {
        $inventory->load('product');
        return view('inventory.edit', compact('inventory'));
    }

    // i-save ang bagong lokasyon, yung quantity hindi dito binabago
    // ang quantity ay binabago lang sa stock movements
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