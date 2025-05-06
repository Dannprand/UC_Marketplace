<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'full_name' => 'Merchant User ' . $i,
                'phone_number' => '0812345678' . $i,
                'email' => 'merchant' . $i . '@example.com',
                'password' => Hash::make('password'), // default password
                'pfp' => 'https://via.placeholder.com/150.png?text=User+' . $i,
                'is_merchant' => true,
                'merchant_password' => Hash::make('merchantpass'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
