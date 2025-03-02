<?php

namespace Modules\Vehicle\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\src\Models\Customer;
use Modules\Vehicle\src\Models\Vehicle;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $makes = ['Toyota', 'Honda', 'Ford', 'Volkswagen', 'BMW', 'Mercedes-Benz', 'Audi', 'Kia', 'Hyundai', 'Nissan'];
        $models = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Yaris', 'Hilux'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'Jazz'],
            'Ford' => ['Focus', 'Fiesta', 'Mustang', 'Escape', 'Ranger'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'Tiguan', 'T-Roc'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', '1 Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'A-Class', 'GLC', 'GLA'],
            'Audi' => ['A3', 'A4', 'Q3', 'Q5', 'A1'],
            'Kia' => ['Sportage', 'Ceed', 'Picanto', 'Rio', 'Stonic'],
            'Hyundai' => ['i30', 'Tucson', 'Kona', 'Santa Fe', 'i20'],
            'Nissan' => ['Qashqai', 'Juke', 'Micra', 'X-Trail', 'Leaf']
        ];

        $make = $this->faker->randomElement($makes);
        $model = $this->faker->randomElement($models[$make]);

        // Format for Cyprus license plates: ABC123 (3 letters followed by 3 numbers)
        $license_plate = strtoupper($this->faker->lexify('???')) . $this->faker->numerify('###');

        return [
            'license_plate' => $license_plate,
            'vin' => $this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}'),
            'make' => $make,
            'model' => $model,
            'year' => $this->faker->numberBetween(2000, 2025),
            'color' => $this->faker->colorName(),
            'engine_number' => $this->faker->regexify('[A-Z0-9]{10}'),
            'engine_type' => $this->faker->randomElement(['Gasoline', 'Diesel', 'Hybrid', 'Electric']),
            'transmission' => $this->faker->randomElement(['Manual', 'Automatic', 'Semi-Automatic']),
            'mileage' => $this->faker->numberBetween(0, 200000),
            'mileage_unit' => 'km',
            'notes' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'sold']),
            'customer_id' => Customer::factory(),
        ];
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'inactive',
            ];
        });
    }
}
