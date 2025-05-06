<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray();
        $merchantIds = Merchant::pluck('id')->toArray();

        for ($i = 1; $i <= 5; $i++) {
            $name = 'Store ' . $i;

            Store::create([
                'merchant_id' => $merchantIds[array_rand($merchantIds)],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'This is ' . $name . ' description.',
                'logo' => 'https://via.placeholder.com/150x150.png?text=Logo+' . $i,
                'banner' => 'https://via.placeholder.com/800x300.png?text=Banner+' . $i,
                'is_published' => true,
                'fundraising_target' => rand(1000000, 10000000),
                'current_funds' => rand(100000, 900000),
                'view_count' => rand(100, 1000),
            ]);
        }
    }
}
