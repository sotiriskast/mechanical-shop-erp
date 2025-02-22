<?php


namespace App\Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerModules();
    }

    public function boot(): void
    {
        $this->bootModules();
    }

    private function registerModules(): void
    {
        // Get all modules
        $modules = File::directories(app_path('Modules'));

        foreach ($modules as $module) {
            // Get module name
            $moduleName = basename($module);

            // Register routes
            if (File::exists($module . '/routes.php')) {
                $this->loadRoutesFrom($module . '/routes.php');
            }

            // Register views
            if (File::exists($module . '/Views')) {
                $this->loadViewsFrom($module . '/Views', $moduleName);
            }

            // Register migrations
            if (File::exists($module . '/Database/Migrations')) {
                $this->loadMigrationsFrom($module . '/Database/Migrations');
            }

            // Register translations
            if (File::exists($module . '/Lang')) {
                $this->loadTranslationsFrom($module . '/Lang', $moduleName);
            }

            // Register module service provider if exists
            $providerClass = "App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    private function bootModules(): void
    {
        // Add module-specific boot logic here
    }
}
