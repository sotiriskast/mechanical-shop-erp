<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $customerPermissions = ['view-customers', 'create-customers', 'edit-customers', 'delete-customers'];
        $vehiclePermissions = ['view-vehicles', 'create-vehicles', 'edit-vehicles', 'delete-vehicles'];
        $workshopPermissions = ['view-work-orders', 'create-work-orders', 'edit-work-orders', 'delete-work-orders', 'assign-work-orders', 'complete-work-orders'];
        $inventoryPermissions = ['view-inventory', 'create-inventory', 'edit-inventory', 'delete-inventory', 'manage-stock'];
        $financePermissions = ['view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'manage-payments', 'view-reports'];

        $allPermissions = array_merge(
            $customerPermissions,
            $vehiclePermissions,
            $workshopPermissions,
            $inventoryPermissions,
            $financePermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'super-admin' => Permission::all(),
            'admin' => [...$customerPermissions, ...$vehiclePermissions, ...$workshopPermissions, ...$inventoryPermissions, 'view-invoices', 'create-invoices', 'view-reports'],
            'manager' => ['view-customers', 'create-customers', 'edit-customers', ...$workshopPermissions, 'view-inventory', 'manage-stock', 'view-invoices', 'create-invoices'],
            'mechanic' => ['view-customers', 'view-vehicles', 'view-work-orders', 'edit-work-orders', 'complete-work-orders', 'view-inventory'],
            'receptionist' => ['view-customers', 'create-customers', 'edit-customers', 'view-vehicles', 'view-work-orders', 'create-work-orders'],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }

        // Create a default super-admin user
        $user = User::firstOrCreate(
            ['email' => 'root@root.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this in production!
            ]
        );

        $user->assignRole('super-admin');
    }
}
