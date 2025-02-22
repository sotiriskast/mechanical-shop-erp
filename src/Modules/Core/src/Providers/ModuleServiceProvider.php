<?php
namespace Modules\Core\src\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

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
        $modules = File::directories(base_path('src/Modules'));

        foreach ($modules as $module) {
            $moduleName = basename($module);

            // Register routes
            $this->registerRoutes($module, $moduleName);

            // Register views, migrations, translations
            $this->registerResources($module, $moduleName);

            // Register module service provider
            $this->registerModuleProvider($moduleName);
        }
    }

    private function registerRoutes(string $module, string $moduleName): void
    {
        if (File::exists($module . '/src/routes/web.php')) {
            $this->loadRoutesFrom($module . '/src/routes/web.php');
        }

        if (File::exists($module . '/src/routes/api.php')) {
            $this->loadRoutesFrom($module . '/src/routes/api.php');
        }
    }

    private function registerResources(string $module, string $moduleName): void
    {
        // Views
        if (File::exists($module . '/resources/views')) {
            $this->loadViewsFrom($module . '/resources/views', $moduleName);
        }

        // Migrations
        if (File::exists($module . '/database/migrations')) {
            $this->loadMigrationsFrom($module . '/database/migrations');
        }

        // Translations
        if (File::exists($module . '/lang')) {
            $this->loadTranslationsFrom($module . '/lang', $moduleName);
        }
    }

    private function registerModuleProvider(string $moduleName): void
    {
        $providerClass = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
        if (class_exists($providerClass)) {
            $this->app->register($providerClass);
        }
    }

    private function bootModules(): void
    {
        // Module-specific boot logic
    }
}
