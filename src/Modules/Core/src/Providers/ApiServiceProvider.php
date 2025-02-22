<?php
namespace Modules\Core\src\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\src\Http\Middleware\ApiVersion;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('api.version', ApiVersion::class);

        // Register global API middleware
        $this->app['router']->middlewareGroup('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'api.version',
        ]);
    }
}
