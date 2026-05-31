<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@inventory.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Staff User',
            'email'    => 'staff@inventory.com',
            'password' => Hash::make('password123'),
            'role'     => 'staff',
        ]);
    }
}