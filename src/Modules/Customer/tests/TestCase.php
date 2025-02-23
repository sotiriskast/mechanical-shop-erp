<?php

namespace Modules\Customer\tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\Database\Factories\CustomerFactory;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Any module-specific setup can go here
    }

    protected function createCustomer(array $attributes = []): Customer
    {
        return Customer::factory()->create(array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ], $attributes));
    }

    protected function getValidCustomerData(): array
    {
        return [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '+1234567890',
            'is_active' => true,
        ];
    }
}
