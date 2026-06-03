<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $users = [
            [
                'name'       => 'Admin User',
                'email'      => 'admin@inventory.com',
                'role'       => 'admin',
            ],
            [
                'name'       => 'Staff User',
                'email'      => 'staff@inventory.com',
                'role'       => 'staff',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->upsert(
                [
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'password'   => Hash::make('password123'),
                    'role'       => $user['role'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                ['email'],
                ['name', 'password', 'role', 'updated_at']
            );
        }
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'admin@inventory.com',
            'staff@inventory.com',
        ])->delete();
    }
};
