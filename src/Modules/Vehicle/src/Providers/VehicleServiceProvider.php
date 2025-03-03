<?php

namespace Modules\Vehicle\src\Providers;

use Modules\Core\src\Providers\BaseModuleServiceProvider;
use Modules\Vehicle\src\Actions\CreateVehicleAction;
use Modules\Vehicle\src\Actions\DeleteVehicleAction;
use Modules\Vehicle\src\Actions\UpdateVehicleAction;
use Modules\Vehicle\src\Actions\CreateServiceHistoryAction;
use Modules\Vehicle\src\Actions\DeleteServiceHistoryAction;
use Modules\Vehicle\src\Actions\UpdateServiceHistoryAction;
use Modules\Vehicle\src\Models\Vehicle;
use Modules\Vehicle\src\Models\ServiceHistory;
use Modules\Vehicle\src\Repositories\VehicleRepository;
use Modules\Vehicle\src\Repositories\ServiceHistoryRepository;
use Modules\Vehicle\src\Services\VehicleMediaService;
use Modules\Vehicle\src\Services\VehicleSearchService;
use Modules\Vehicle\src\Services\VehicleService;
use Modules\Vehicle\src\Services\ServiceHistoryService;

class VehicleServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Vehicle';
    protected string $moduleNamespace = 'Modules\Vehicle';

    protected function registerBindings(): void
    {
        // Register repositories
        $this->app->bind(VehicleRepository::class, function ($app) {
            return new VehicleRepository($app->make(Vehicle::class));
        });

        $this->app->bind(ServiceHistoryRepository::class, function ($app) {
            return new ServiceHistoryRepository($app->make(ServiceHistory::class));
        });

        // Register vehicle actions as singletons
        $this->app->singleton(CreateVehicleAction::class);
        $this->app->singleton(UpdateVehicleAction::class);
        $this->app->singleton(DeleteVehicleAction::class);

        // Register service history actions as singletons
        $this->app->singleton(CreateServiceHistoryAction::class);
        $this->app->singleton(UpdateServiceHistoryAction::class);
        $this->app->singleton(DeleteServiceHistoryAction::class);

        // Register services
        $this->app->singleton(VehicleMediaService::class);
        $this->app->singleton(VehicleSearchService::class);

        $this->app->bind(VehicleService::class, function ($app) {
            return new VehicleService(
                $app->make(VehicleRepository::class),
                $app->make(CreateVehicleAction::class),
                $app->make(UpdateVehicleAction::class),
                $app->make(DeleteVehicleAction::class)
            );
        });

        $this->app->bind(ServiceHistoryService::class, function ($app) {
            return new ServiceHistoryService(
                $app->make(ServiceHistoryRepository::class),
                $app->make(CreateServiceHistoryAction::class),
                $app->make(UpdateServiceHistoryAction::class),
                $app->make(DeleteServiceHistoryAction::class)
            );
        });
    }

    protected function bootModule(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../src/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../src/routes/web.php');
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'vehicle');
    }
}
