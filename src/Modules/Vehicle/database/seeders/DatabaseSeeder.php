<?php

namespace Modules\Vehicle\database\seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VehicleSeeder::class,
            VehiclePermissionSeeder::class,
        ]);
    }
}
