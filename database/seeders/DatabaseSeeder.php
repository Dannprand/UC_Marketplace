<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\PaymentMethod;
use App\Models\MerchantPaymentMethod;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 10 users with addresses and payment methods
        User::factory(10)->create()->each(function ($user) {
            // Create address for each user
            Address::factory()->create([
                'user_id' => $user->id,
            ]);

            // Create payment methods for each user
            PaymentMethod::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);

            // Randomly make some users merchants
            if (rand(0, 1)) {
                $merchant = Merchant::factory()->create([
                    'user_id' => $user->id,
                    'status' => 'active',
                ]);

                // Create merchant payment methods
                MerchantPaymentMethod::factory(rand(1, 2))->create([
                    'merchant_id' => $merchant->id,
                ]);

                // Create store for merchant
                $store = Store::factory()->create([
                    'merchant_id' => $merchant->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'is_published' => true,
                ]);

                // Create products for store
                Product::factory(rand(5, 15))->create([
                    'store_id' => $store->id,
                    'category_id' => Category::inRandomOrder()->first()->id,
                ]);
            }
        });

        // Create categories
        $categories = [
            'Electronics', 'Fashion', 'Home & Living', 'Beauty', 'Sports', 
            'Books', 'Food & Beverages', 'Health', 'Toys', 'Stationery'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => "Products related to {$category}",
            ]);
        }

        // Create a test user with known credentials
        $testUser = User::create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_merchant' => true,
        ]);

        Address::create([
            'user_id' => $testUser->id,
            'street' => '123 Test Street',
            'city' => 'Surabaya',
            'province' => 'East Java',
            'postal_code' => '60293',
            'country' => 'Indonesia',
            'is_primary' => true,
        ]);

        $merchant = Merchant::create([
            'user_id' => $testUser->id,
            'merchant_name' => 'Test Merchant',
            'status' => 'active',
        ]);

        Store::create([
            'merchant_id' => $merchant->id,
            'category_id' => Category::first()->id,
            'name' => 'Test Store',
            'slug' => 'test-store',
            'description' => 'This is a test store for demonstration purposes',
            'is_published' => true,
        ]);
    }
}