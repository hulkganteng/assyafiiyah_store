<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Fashion Muslim', 'slug' => 'fashion-muslim']);
        Category::create(['name' => 'Kitab & Buku', 'slug' => 'kitab-buku']);
        Category::create(['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman']);
        Category::create(['name' => 'Herbal & Kesehatan', 'slug' => 'herbal-kesehatan']);
    }
}
