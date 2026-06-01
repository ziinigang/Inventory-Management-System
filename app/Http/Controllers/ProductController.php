<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // LIST all products with search + filter
    public function index(Request $request)
    {
        $query = Product::with(['supplier', 'inventory']);

        // Search by name or SKU
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $products   = $query->latest()->paginate(10);
        $suppliers  = Supplier::where('status', 'active')->get();
        $categories = Product::distinct()->pluck('category');

        return view('products.index', compact('products', 'suppliers', 'categories'));
    }

    // SHOW create form
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $sku       = $this->generateSku();

        return view('products.create', compact('suppliers', 'sku'));
    }

    // STORE new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['required', 'string', 'unique:products,sku', 'max:50'],
            'category'      => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'location'      => ['nullable', 'string', 'max:100'],
        ]);

        // Create the product
        $product = Product::create([
            'supplier_id'   => $validated['supplier_id'],
            'name'          => $validated['name'],
            'sku'           => $validated['sku'],
            'category'      => $validated['category'],
            'description'   => $validated['description'],
            'price'         => $validated['price'],
            'reorder_level' => $validated['reorder_level'],
        ]);

        // Create the linked inventory record
        Inventory::create([
            'product_id' => $product->id,
            'quantity'   => $validated['quantity'],
            'location'   => $validated['location'],
        ]);

        // Record the initial stock movement if quantity > 0
        if ($validated['quantity'] > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'in',
                'quantity'   => $validated['quantity'],
                'reason'     => 'Initial stock on product creation',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', "Product '{$product->name}' created successfully.");
    }

    // SHOW single product detail
    public function show(Product $product)
    {
        $product->load(['supplier', 'inventory', 'stockMovements.user']);
        $recentMovements = $product->stockMovements()->latest()->take(5)->get();

        return view('products.show', compact('product', 'recentMovements'));
    }

    // SHOW edit form
    public function edit(Product $product)
    {
        $suppliers = Supplier::where('status', 'active')->get();

        return view('products.edit', compact('product', 'suppliers'));
    }

    // UPDATE product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['required', 'string', 'max:50',
                                'unique:products,sku,' . $product->id],
            'category'      => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'location'      => ['nullable', 'string', 'max:100'],
        ]);

        $product->update([
            'supplier_id'   => $validated['supplier_id'],
            'name'          => $validated['name'],
            'sku'           => $validated['sku'],
            'category'      => $validated['category'],
            'description'   => $validated['description'],
            'price'         => $validated['price'],
            'reorder_level' => $validated['reorder_level'],
        ]);

        // Update inventory location if provided
        if ($product->inventory) {
            $product->inventory->update([
                'location' => $validated['location'],
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', "Product '{$product->name}' updated successfully.");
    }

    // DELETE product
    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Product '{$name}' deleted successfully.");
    }

    // Auto-generate a unique SKU
    private function generateSku(): string
    {
        $prefix = 'SKU-';
        $last   = Product::latest('id')->first();
        $next   = $last ? ($last->id + 1) : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}