<?php

namespace Modules\Customer\tests\Unit\Models;

use Modules\Customer\tests\TestCase;
use Modules\Customer\src\Models\Customer;

class CustomerModelTest extends TestCase
{
    /** @test */
    public function it_can_create_a_customer()
    {
        $customer = Customer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com'
        ]);

        $this->assertDatabaseHas('customers', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com'
        ]);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('John', $customer->first_name);
        $this->assertEquals('John Doe', $customer->full_name);
    }

    /** @test */
    public function it_generates_a_customer_code_automatically()
    {
        $customer = Customer::factory()->create();

        $this->assertNotNull($customer->code);
        $this->assertStringStartsWith('CUS-', $customer->code);
    }

    /** @test */
    public function it_can_create_an_inactive_customer()
    {
        $customer = Customer::factory()->inactive()->create();

        $this->assertFalse($customer->is_active);
    }
}
