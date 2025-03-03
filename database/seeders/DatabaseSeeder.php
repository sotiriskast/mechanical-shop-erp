<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
        $this->call([
            \Modules\Vehicle\database\seeders\DatabaseSeeder::class,
            \Modules\Customer\database\seeders\DatabaseSeeder::class,
            // Add other module seeders as needed
        ]);
    }
}
