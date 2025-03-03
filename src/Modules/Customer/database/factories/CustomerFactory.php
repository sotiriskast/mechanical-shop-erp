<?php

namespace Modules\Customer\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\src\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->numerify('+3579########'), // Generates unique phone numbers
            'mobile' => $this->faker->numerify('+3579########'),
            'company_name' => $this->faker->company(),
            'vat_number' => $this->faker->numerify('####'),
            'tax_office' => $this->faker->city(),
            'billing_address' => $this->faker->streetAddress(),
            'shipping_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'is_active' => 1,
            'notes' => $this->faker->optional()->text(),
            'uuid' => $this->faker->uuid(),
            'code' => 'CUS-' . str_pad($this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
        ];
    }
}
