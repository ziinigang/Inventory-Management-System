<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $movements = [
            [
                'product_id' => 1,
                'user_id'    => 1,
                'type'       => 'in',
                'quantity'   => 150,
                'reason'     => 'Initial stock from ABC Trading Corp',
            ],
            [
                'product_id' => 1,
                'user_id'    => 1,
                'type'       => 'out',
                'quantity'   => 30,
                'reason'     => 'Issued to Department A',
            ],
            [
                'product_id' => 2,
                'user_id'    => 1,
                'type'       => 'in',
                'quantity'   => 50,
                'reason'     => 'Restocking from supplier',
            ],
            [
                'product_id' => 2,
                'user_id'    => 2,
                'type'       => 'out',
                'quantity'   => 5,
                'reason'     => 'Issued to HR Department',
            ],
            [
                'product_id' => 3,
                'user_id'    => 1,
                'type'       => 'in',
                'quantity'   => 10,
                'reason'     => 'Initial stock from XYZ Supplies',
            ],
            [
                'product_id' => 3,
                'user_id'    => 2,
                'type'       => 'adjustment',
                'quantity'   => -2,
                'reason'     => 'Damaged items removed from inventory',
            ],
        ];

        foreach ($movements as $movement) {
            StockMovement::create($movement);
        }
    }
}