<?php

namespace Modules\Vehicle\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Models\Vehicle;

class ServiceHistoryFactory extends Factory
{
    protected $model = ServiceHistory::class;

    public function definition(): array
    {
        $serviceTypes = [
            'Oil Change',
            'Tire Rotation',
            'Brake Service',
            'Engine Tune-Up',
            'Air Filter Replacement',
            'Battery Replacement',
            'Coolant Flush',
            'Transmission Service',
            'Wheel Alignment',
            'Emission Test'
        ];

        return [
            'vehicle_id' => Vehicle::factory(),
            'service_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'service_type' => $this->faker->randomElement($serviceTypes),
            'description' => $this->faker->paragraph(),
            'mileage' => $this->faker->numberBetween(10000, 150000),
            'mileage_unit' => 'km',
            'technician_name' => $this->faker->name(),
            'cost' => $this->faker->randomFloat(2, 50, 2000),
            'status' => $this->faker->randomElement(['completed', 'scheduled', 'in_progress', 'cancelled']),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function incomplete(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement(['scheduled', 'in_progress']),
            ];
        });
    }
}
