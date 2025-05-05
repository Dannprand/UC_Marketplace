<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition()
    {
        return [
            'street' => $this->faker->streetAddress(),
            'city' => 'Surabaya',
            'province' => 'East Java',
            'postal_code' => $this->faker->postcode(),
            'country' => 'Indonesia',
            'is_primary' => true,
        ];
    }
}