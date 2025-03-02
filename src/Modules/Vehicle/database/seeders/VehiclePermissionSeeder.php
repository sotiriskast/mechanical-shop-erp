<?php

namespace Modules\Vehicle\database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VehiclePermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Create permissions for vehicles
            $permissions = [
                'view-vehicles',
                'create-vehicles',
                'edit-vehicles',
                'delete-vehicles',
            ];

            foreach ($permissions as $permission) {
                // Use create instead of findOrCreate if findOrCreate is failing
                Permission::firstOrCreate(['name' => $permission]);
            }

            // Add these permissions to admin role
            $adminRole = Role::firstWhere('name', 'admin');
            if ($adminRole) {
                $adminRole->syncPermissions($permissions);
            } else {
                // Create admin role if it doesn't exist
                $adminRole = Role::create(['name' => 'admin']);
                $adminRole->syncPermissions($permissions);
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Permission Seeder Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
