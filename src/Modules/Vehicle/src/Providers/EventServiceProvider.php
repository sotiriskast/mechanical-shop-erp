<?php
namespace Modules\Vehicle\src\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Vehicle\src\Events\VehicleCreated;
use Modules\Vehicle\src\Events\VehicleUpdated;
use Modules\Vehicle\src\Events\VehicleDeleted;
use Modules\Vehicle\src\Events\ServiceHistoryCreated;
use Modules\Vehicle\src\Events\ServiceHistoryUpdated;
use Modules\Vehicle\src\Events\ServiceHistoryDeleted;
use Modules\Vehicle\src\Listeners\SendVehicleNotifications;
use Modules\Vehicle\src\Listeners\SendServiceHistoryNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VehicleCreated::class => [
            SendVehicleNotifications::class,
        ],
        VehicleUpdated::class => [
            SendVehicleNotifications::class,
        ],
        VehicleDeleted::class => [
            SendVehicleNotifications::class,
        ],
        ServiceHistoryCreated::class => [
            SendServiceHistoryNotifications::class,
        ],
        ServiceHistoryUpdated::class => [
            SendServiceHistoryNotifications::class,
        ],
        ServiceHistoryDeleted::class => [
            SendServiceHistoryNotifications::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
