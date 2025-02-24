<?php

namespace Modules\Customer\src\Providers;

use Modules\Core\src\Providers\BaseModuleServiceProvider;
use Modules\Customer\src\Actions\CreateCustomerAction;
use Modules\Customer\src\Actions\DeleteCustomerAction;
use Modules\Customer\src\Actions\UpdateCustomerAction;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Repositories\CustomerRepository;
use Modules\Customer\src\Services\CustomerMediaService;
use Modules\Customer\src\Services\CustomerSearchService;
use Modules\Customer\src\Services\CustomerService;

class CustomerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Customer';
    protected string $moduleNamespace = 'Modules\Customer';

    protected function registerBindings(): void
    {

        // Bind concrete implementations
        $this->app->bind(CustomerRepository::class, function ($app) {
            return new CustomerRepository($app->make(Customer::class));
        });

        // Register actions as singletons
        $this->app->singleton(CreateCustomerAction::class);
        $this->app->singleton(UpdateCustomerAction::class);
        $this->app->singleton(DeleteCustomerAction::class);

        // Register services
        $this->app->singleton(CustomerMediaService::class);
        $this->app->singleton(CustomerSearchService::class);

        $this->app->bind(CustomerService::class, function ($app) {
            return new CustomerService(
                $app->make(CustomerRepository::class),
                $app->make(CreateCustomerAction::class),
                $app->make(UpdateCustomerAction::class),
                $app->make(DeleteCustomerAction::class)
            );
        });
    }

    protected function bootModule(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../src/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../src/routes/web.php');
    }
}
