<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $storeIds = Store::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 20; $i++) {
            $name = 'Product ' . $i;

            Product::create([
                'store_id' => $storeIds[array_rand($storeIds)],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'This is a dummy description for ' . $name,
                'price' => rand(10000, 100000),
                'quantity' => rand(10, 100),
                'sold_amount' => rand(0, 50),
                'images' => [
                    'https://via.placeholder.com/300x200.png?text=' . urlencode($name),
                    'https://via.placeholder.com/300x200.png?text=Image+2',
                ],
                'is_discounted' => rand(0, 1),
                'discount_percentage' => rand(0, 30),
                'is_featured' => rand(0, 1),
                'rating' => rand(30, 50) / 10,
                'review_count' => rand(0, 100),
            ]);
        }
    }
}
