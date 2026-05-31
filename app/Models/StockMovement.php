<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reason',
    ];

    // A movement belongs to one product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // A movement was recorded by one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}