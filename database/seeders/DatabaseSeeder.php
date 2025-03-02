<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\database\Seeders\CustomerSeeder;
use Modules\Vehicle\database\seeders\VehiclePermissionSeeder;
use Modules\Vehicle\database\seeders\VehicleSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CustomerSeeder::class,
            VehicleSeeder::class,
            VehiclePermissionSeeder::class,
        ]);

    }
}
