<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Food',
            'Drinks',
            'Snacks',
            'Merchandise',
            'Stationery',
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'description' => "All about $cat products",
                'image' => 'https://via.placeholder.com/300x200.png?text=' . urlencode($cat),
            ]);
        }
    }
}
