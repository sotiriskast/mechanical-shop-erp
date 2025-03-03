<?php

namespace Modules\Vehicle\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\src\Models\Customer;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Models\ServiceHistory;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Get all customers
        $customers = Customer::factory()->count(20)->create();

        if ($customers->isEmpty()) {
            // Create customers if none exist
            $customers = Customer::factory()->count(20)->create();
        }

        // Create 2-3 vehicles for each customer
        $customers->each(function ($customer) {
            $vehicles = Vehicle::factory()
                ->count(rand(1, 3))
                ->create(['customer_id' => $customer->id]);

            // Create 3-10 service history entries for each vehicle
            $vehicles->each(function ($vehicle) {
                ServiceHistory::factory()
                    ->count(rand(3, 10))
                    ->create(['vehicle_id' => $vehicle->id]);
            });
        });
    }
}
