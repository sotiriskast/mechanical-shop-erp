<?php
namespace Modules\Customer\src\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Customer\src\Events\CustomerCreated;
use Modules\Customer\src\Events\CustomerUpdated;
use Modules\Customer\src\Events\CustomerDeleted;
use Modules\Customer\src\Listeners\SendCustomerNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CustomerCreated::class => [
            SendCustomerNotifications::class,
        ],
        CustomerUpdated::class => [
            SendCustomerNotifications::class,
        ],
        CustomerDeleted::class => [
            SendCustomerNotifications::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
