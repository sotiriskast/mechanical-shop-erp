<?php

namespace Modules\Core\src\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\Commands\MakeModuleMigration;
use Modules\Core\src\Http\Middleware\PermissionMiddleware;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MakeModuleMigration::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('permission', PermissionMiddleware::class);

    }
}
