<?php

namespace Modules\Customer\src\Providers;

use Modules\Core\src\Providers\BaseModuleServiceProvider;
use Modules\Customer\src\Models\Customer;
use Modules\Customer\src\Repositories\CustomerRepository;
use Modules\Customer\src\Services\CustomerService;

class CustomerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Customer';
    protected string $moduleNamespace = 'Modules\Customer';

    protected function registerBindings(): void
    {
        $this->app->bind(CustomerRepository::class, function ($app) {
            return new CustomerRepository($app->make(Customer::class));
        });

        $this->app->bind(CustomerService::class, function ($app) {
            return new CustomerService(
                $app->make(CustomerRepository::class)
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
