<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => 'fas fa-tv', 'is_active' => true],
            ['name' => 'Fashion Pria', 'slug' => 'fashion-pria', 'icon' => 'fas fa-tshirt', 'is_active' => true],
            ['name' => 'Fashion Wanita', 'slug' => 'fashion-wanita', 'icon' => 'fas fa-female', 'is_active' => true],
            ['name' => 'Handphone & Tablet', 'slug' => 'handphone-tablet', 'icon' => 'fas fa-mobile-alt', 'is_active' => true],
            ['name' => 'Komputer & Laptop', 'slug' => 'komputer-laptop', 'icon' => 'fas fa-laptop', 'is_active' => true],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'icon' => 'fas fa-heartbeat', 'is_active' => true],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'icon' => 'fas fa-running', 'is_active' => true],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'icon' => 'fas fa-utensils', 'is_active' => true],
            ['name' => 'Otomotif', 'slug' => 'otomotif', 'icon' => 'fas fa-car', 'is_active' => true],
            ['name' => 'Perlengkapan Rumah', 'slug' => 'perlengkapan-rumah', 'icon' => 'fas fa-home', 'is_active' => true],
            ['name' => 'Buku & Alat Tulis', 'slug' => 'buku-alat-tulis', 'icon' => 'fas fa-book', 'is_active' => true],
            ['name' => 'Mainan & Hobi', 'slug' => 'mainan-hobi', 'icon' => 'fas fa-gamepad', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
