<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // dito makikita ang buong listahan ng inventory kasama ang stock levels of every product
    public function index(Request $request)
    {
        // jinoin natin ang inventories at products table para makuha ang complete info
        $query = Inventory::with(['product.supplier'])
                          ->join('products', 'inventories.product_id', '=', 'products.id');

        // kapag nagtype ang user sa search bar, hahanapin natin sa product name o SKU
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', '%' . $request->search . '%')
                  ->orWhere('products.sku', 'like', '%' . $request->search . '%');
            });
        }

        // kung nag-filter ang user by stock status, dito natin sinesegregate ang results
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                // Low stock —  low but not zero, need to reorder
                $query->whereRaw('inventories.quantity <= products.reorder_level');
            } elseif ($request->stock_status === 'out') {
                // Out of stock — zero, need to reorder asap
                $query->where('inventories.quantity', '=', 0);
            } elseif ($request->stock_status === 'ok') {
                // Okay pa — allgoods pa ang stock, hindi pa aabot sa reorder level
                $query->whereRaw('inventories.quantity > products.reorder_level');
            }
        }

        // kunin ang results ung pinaka-recently updated muna, then i-paginate ng 10 per page
        $inventories = $query->select('inventories.*')
                             ->latest('inventories.updated_at')
                             ->paginate(10);

        // ito yung mga bilang na makikita sa summary cards sa taas ng page
        $stats = [
            'total'   => Inventory::count(), // kabuuang bilang ng lahat ng items sa inventory

            // ilan ba yung may mababang stock pero hindi pa zero
            'low'     => Inventory::join('products', 'inventories.product_id', '=', 'products.id')
                                  ->whereRaw('inventories.quantity <= products.reorder_level')
                                  ->where('inventories.quantity', '>', 0)
                                  ->count(),

            // ilan yung completely wala nang stock
            'out'     => Inventory::where('quantity', 0)->count(),

            // ilan yung may healthy o sufficient na stock pa
            'healthy' => Inventory::join('products', 'inventories.product_id', '=', 'products.id')
                                  ->whereRaw('inventories.quantity > products.reorder_level')
                                  ->count(),
        ];

        return view('inventory.index', compact('inventories', 'stats'));
    }

    // dito naman makikita ang detailed view ng isang product — kasama na yung buong stock movement history niya
    public function show(Inventory $inventory)
    {
        // iload ang supplier info at lahat ng stock movements ng produkto, kasama kung sino ang gumawa
        $inventory->load(['product.supplier', 'product.stockMovements.user']);

        // ipinapakita ang movements mula pinaka-bago, paginated din para hindi overwhelming
        $movements = $inventory->product
                               ->stockMovements()
                               ->with('user')
                               ->latest()
                               ->paginate(10);

        return view('inventory.show', compact('inventory', 'movements'));
    }

    // dito pumupunta ang user kapag gusto niyang baguhin ang storage location ng isang produkto
    public function edit(Inventory $inventory)
    {
        $inventory->load('product');
        return view('inventory.edit', compact('inventory'));
    }

    // isave ang updated na location — quantity hindi dito binabago, sa Stock Movements lang yun ginagawa
    public function update(Request $request, Inventory $inventory)
    {
        // ivalidate muna — location lang ang pwedeng i-update dito, optional pa nga
        $validated = $request->validate([
            'location' => ['nullable', 'string', 'max:100'],
        ]);

        $inventory->update($validated);

        // pagkatapos i-save, ibalik ang user sa inventory list with success message
        return redirect()->route('inventory.index')
            ->with('success', 'Storage location updated.');
    }
}