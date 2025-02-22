<?php
namespace Modules\Customer\database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Customer\src\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('el_CY');

        for ($i = 0; $i < 100; $i++) {
            Customer::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => '+35725' . $faker->numberBetween(100000, 999999),
                'mobile' => '+35799' . $faker->numberBetween(100000, 999999),
                'company_name' => $faker->optional(0.3)->company(),
                'vat_number' => 'CY' . $faker->numberBetween(10000000, 99999999) . 'X',
                'tax_office' => $faker->randomElement(['Nicosia', 'Limassol', 'Larnaca', 'Paphos', 'Famagusta']),
                'billing_address' => $faker->address(),
                'shipping_address' => $faker->optional(0.3)->address(),
                'city' => $faker->city(),
                'postal_code' => $faker->numberBetween(1000, 9999),
                'country' => 'Cyprus',
                'notes' => $faker->optional(0.5)->text(),
                'is_active' => $faker->boolean(80),
            ]);
        }
    }
}
