<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming categories IDs are 1-4
        $products = [
            [
                'category_id' => 1,
                'name' => 'Baju Koko Modern',
                'slug' => 'baju-koko-modern',
                'description' => 'Baju koko bahan katun premium, nyaman dipakai sholat dan kegiatan sehari-hari.',
                'price' => 150000,
                'stock' => 50,
            ],
            [
                'category_id' => 1,
                'name' => 'Gamis Syari',
                'slug' => 'gamis-syari',
                'description' => 'Gamis set syari dengan bahan wolfis, tidak menerawang dan adem.',
                'price' => 250000,
                'stock' => 30,
            ],
            [
                'category_id' => 2,
                'name' => 'Kitab Fathul Qarib',
                'slug' => 'kitab-fathul-qarib',
                'description' => 'Kitab fiqh dasar cetakan terbaru, kertas hvs.',
                'price' => 35000,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Al-Quran Terjemah',
                'slug' => 'al-quran-terjemah',
                'description' => 'Al-Quran dengan terjemahan bahasa Indonesia, ukuran A5.',
                'price' => 75000,
                'stock' => 80,
            ],
            [
                'category_id' => 3,
                'name' => 'Madu Hutan Asli',
                'slug' => 'madu-hutan-asli',
                'description' => 'Madu murni dari hutan liar, berkhasiat untuk kesehatan.',
                'price' => 120000,
                'stock' => 45,
            ],
            [
                'category_id' => 3,
                'name' => 'Kurma Ajwa',
                'slug' => 'kurma-ajwa',
                'description' => 'Kurma nabi kualitas premium 1kg.',
                'price' => 200000,
                'stock' => 20,
            ],
            [
                'category_id' => 4,
                'name' => 'Minyak Zaitun',
                'slug' => 'minyak-zaitun',
                'description' => 'Minyak zaitun extra virgin 500ml.',
                'price' => 85000,
                'stock' => 60,
            ],
             [
                'category_id' => 4,
                'name' => 'Habbatussauda Kapsul',
                'slug' => 'habbatussauda-kapsul',
                'description' => 'Kapsul jintan hitam isi 100.',
                'price' => 50000,
                'stock' => 150,
            ],
            [
                'category_id' => 1,
                'name' => 'Peci Rajut',
                'slug' => 'peci-rajut',
                'description' => 'Peci rajut nyaman elastis.',
                'price' => 10000,
                'stock' => 200,
            ],
            [
                'category_id' => 3,
                'name' => 'Kopiah Hitam Polos',
                'slug' => 'kopiah-hitam-polos',
                'description' => 'Kopiah hitam nasional bahan beludru halus.',
                'price' => 45000,
                'stock' => 75,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
