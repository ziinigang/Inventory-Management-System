<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // LIST all suppliers
    public function index(Request $request)
    {
        $query = Supplier::withCount('products');

        // Search by name, email, or contact person
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->latest()->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    // SHOW create form
    public function create()
    {
        return view('suppliers.create');
    }

    // STORE new supplier
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:suppliers,email'],
            'phone'          => ['required', 'string', 'max:20'],
            'address'        => ['required', 'string'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' added successfully.");
    }

    // SHOW supplier detail + their products
    public function show(Supplier $supplier)
    {
        $supplier->load(['products.inventory']);
        $products      = $supplier->products()->with('inventory')->latest()->paginate(8);
        $totalProducts = $supplier->products()->count();
        $totalStock    = $supplier->products()
                             ->join('inventories', 'products.id', '=', 'inventories.product_id')
                             ->sum('inventories.quantity');

        return view('suppliers.show', compact('supplier', 'products', 'totalProducts', 'totalStock'));
    }

    // SHOW edit form
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    // UPDATE supplier
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email',
                                 'unique:suppliers,email,' . $supplier->id],
            'phone'          => ['required', 'string', 'max:20'],
            'address'        => ['required', 'string'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' updated successfully.");
    }

    // DELETE supplier
    public function destroy(Supplier $supplier)
    {
        // Prevent deletion if supplier still has products
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', "Cannot delete '{$supplier->name}'.
                                 Remove all linked products first.");
        }

        $name = $supplier->name;
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$name}' deleted successfully.");
    }

    // TOGGLE status (active ↔ inactive) via AJAX-friendly route
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update([
            'status' => $supplier->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()->back()
            ->with('success', "'{$supplier->name}' is now {$supplier->status}.");
    }
}