<?php
namespace App\Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = '';
    protected string $moduleNamespace = '';

    public function register(): void
    {
        // Register module bindings
        $this->registerBindings();

        // Register module commands
        $this->registerCommands();

        // Register module configs
        $this->registerConfigs();
    }

    public function boot(): void
    {
        // Boot module specific logic
        $this->bootModule();
    }

    abstract protected function registerBindings(): void;

    protected function registerCommands(): void
    {
        // Register module specific commands
    }

    protected function registerConfigs(): void
    {
        $configPath = $this->getModulePath() . '/Config';

        if (file_exists($configPath)) {
            $this->mergeConfigFrom(
                $configPath . '/config.php',
                $this->moduleName
            );
        }
    }

    abstract protected function bootModule(): void;

    protected function getModulePath(): string
    {
        return app_path('Modules/' . $this->moduleName);
    }
}
