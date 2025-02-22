<?php
namespace Modules\Core\src\Providers;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = '';
    protected string $moduleNamespace = '';

    public function register(): void
    {
        $this->registerBindings();
        $this->registerCommands();
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->bootModule();
    }

    abstract protected function registerBindings(): void;

    protected function registerCommands(): void
    {
        // Register module specific commands
    }

    protected function registerConfigs(): void
    {
        $configPath = $this->getModulePath() . '/config/config.php';

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->moduleName);
        }
    }

    abstract protected function bootModule(): void;

    protected function getModulePath(): string
    {
        return base_path('src/Modules/' . $this->moduleName);
    }
}
