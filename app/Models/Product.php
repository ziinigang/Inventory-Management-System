<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'supplier_id',
        'name',
        'sku',
        'category',
        'description',
        'price',
        'reorder_level',
    ];

    // A product belongs to one supplier
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // A product has one inventory record
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    // A product has many stock movements
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Helper: is this product low on stock?
    public function isLowStock(): bool
    {
        return $this->inventory
            && $this->inventory->quantity <= $this->reorder_level;
    }
}