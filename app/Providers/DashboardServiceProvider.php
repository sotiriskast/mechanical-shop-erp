<?php


namespace App\Providers;

use App\Services\DashboardService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\DashboardPolicy;
use App\Models\User;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DashboardService::class, function ($app) {
            return new DashboardService();
        });
    }

    public function boot(): void
    {
        // Register policies
        Gate::define('view-dashboard', [DashboardPolicy::class, 'viewDashboard']);
        Gate::define('view-reports', [DashboardPolicy::class, 'viewReports']);

        // Add default permissions to roles if not exists
        if ($this->app->environment() !== 'production') {
            $this->bootDevPermissions();
        }
    }

    protected function bootDevPermissions(): void
    {
        try {
            $permissions = [
                'view-dashboard',
                'view-reports',
            ];

            foreach ($permissions as $permission) {
                if (!app(config('permission.models.permission'))->where('name', $permission)->exists()) {
                    app(config('permission.models.permission'))->create(['name' => $permission]);
                }
            }

            // Assign permissions to admin role
            $adminRole = app(config('permission.models.role'))->where('name', 'admin')->first();
            if ($adminRole) {
                $adminRole->givePermissionTo($permissions);
            }
        } catch (\Exception $e) {
            // Log error but don't fail boot process
            logger()->error('Failed to boot dashboard permissions: ' . $e->getMessage());
        }
    }
}
