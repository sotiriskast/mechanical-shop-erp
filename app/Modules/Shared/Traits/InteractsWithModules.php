<?php
namespace App\Modules\Core\Traits;

trait InteractsWithModules
{
    protected function getModulePath(string $module): string
    {
        return app_path("Modules/{$module}");
    }

    protected function getModuleNamespace(string $module): string
    {
        return "App\\Modules\\{$module}";
    }
}
