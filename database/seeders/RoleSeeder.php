<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full access to all system features'],
            ['name' => 'Supervisor', 'slug' => 'supervisor', 'description' => 'Monitor transactions, verify payments, manage complaints'],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Regular marketplace user'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
