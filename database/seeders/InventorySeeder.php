<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $inventories = [
            ['product_id' => 1, 'quantity' => 120, 'location' => 'Shelf A-1'],
            ['product_id' => 2, 'quantity' => 45,  'location' => 'Shelf A-2'],
            ['product_id' => 3, 'quantity' => 8,   'location' => 'Shelf B-1'],
            ['product_id' => 4, 'quantity' => 60,  'location' => 'Shelf B-2'],
            ['product_id' => 5, 'quantity' => 15,  'location' => 'Shelf C-1'],
            ['product_id' => 6, 'quantity' => 5,   'location' => 'Shelf C-2'],
        ];

        foreach ($inventories as $inventory) {
            Inventory::updateOrCreate(
                ['product_id' => $inventory['product_id']], // find by this
                ['quantity'   => $inventory['quantity'], 'location' => $inventory['location']] // update/create these
            );
        }
    }
}