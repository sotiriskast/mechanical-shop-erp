<?php

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\src\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+35799123456',
            'mobile' => '+35799123456',
            'company_name' => $this->faker->company(),
            'vat_number' => $this->faker->unique()->numberBetween(1000, 9999),
            'tax_office' => $this->faker->city(),
            'billing_address' => $this->faker->streetAddress(),
            'shipping_address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'is_active' => $this->faker->boolean(80),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withCompany(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_name' => $this->faker->company(),
            'vat_number' => $this->faker->unique()->numberBetween(1000, 9999),
        ]);
    }
}
