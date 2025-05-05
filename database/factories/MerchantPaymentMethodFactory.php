<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantPaymentMethodFactory extends Factory
{
    public function definition()
    {
        $types = ['bank_transfer', 'e-wallet'];
        $type = $this->faker->randomElement($types);
        
        $providers = [
            'bank_transfer' => ['BCA', 'BRI', 'Mandiri', 'BNI'],
            'e-wallet' => ['Gopay', 'OVO', 'Dana', 'LinkAja'],
        ];
        
        return [
            'type' => $type,
            'provider' => $this->faker->randomElement($providers[$type]),
            'account_name' => $this->faker->name(),
            'account_number' => $this->faker->bankAccountNumber(),
            'instructions' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}