<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    public function definition()
    {
        $types = ['credit_card', 'bank_transfer', 'e-wallet'];
        $type = $this->faker->randomElement($types);
        
        $providers = [
            'credit_card' => ['VISA', 'MasterCard', 'JCB'],
            'bank_transfer' => ['BCA', 'BRI', 'Mandiri', 'BNI'],
            'e-wallet' => ['Gopay', 'OVO', 'Dana', 'LinkAja'],
        ];
        
        return [
            'type' => $type,
            'provider' => $this->faker->randomElement($providers[$type]),
            'account_name' => $this->faker->name(),
            'account_number' => $this->faker->creditCardNumber(),
            'expiry_date' => $type === 'credit_card' ? $this->faker->dateTimeBetween('+1 year', '+5 years') : null,
            'is_default' => $this->faker->boolean(70),
        ];
    }
}