<?php

namespace Modules\Vehicle\src\Listeners;

use Modules\Vehicle\src\Events\VehicleCreated;
use Modules\Vehicle\src\Events\VehicleUpdated;
use Modules\Vehicle\src\Notifications\VehicleCreatedNotification;
use Modules\Vehicle\src\Notifications\VehicleUpdatedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendVehicleNotifications
{
    public function handle($event)
    {
        // Get users with relevant permissions
        $users = User::permission(['view-vehicles'])->get();

        // Determine notification type based on event
        $notification = match(get_class($event)) {
            VehicleCreated::class => new VehicleCreatedNotification($event->vehicle),
            VehicleUpdated::class => new VehicleUpdatedNotification($event->vehicle),
            default => null
        };

        if ($notification) {
            Notification::send($users, $notification);
        }
    }
}
