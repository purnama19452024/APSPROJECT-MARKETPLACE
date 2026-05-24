<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Marketplace',
            'username' => 'admin',
            'email' => 'admin@marketplace.test',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Supervisor Marketplace',
            'username' => 'supervisor',
            'email' => 'supervisor@marketplace.test',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User Marketplace',
            'username' => 'user',
            'email' => 'user@marketplace.test',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'is_active' => true,
        ]);
    }
}
