<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users who are merchants
        $merchantUsers = User::where('is_merchant', true)->get();

        foreach ($merchantUsers as $user) {
            Merchant::create([
                'user_id' => $user->id,
                'merchant_name' => 'Merchant ' . $user->full_name,
                'merchant_description' => 'Description for ' . $user->full_name,
                'merchant_pfp' => $user->pfp,
                'status' => 'active',
                'rating' => rand(30, 50) / 10,
                'total_sales' => rand(100, 1000),
            ]);
        }
    }
}
