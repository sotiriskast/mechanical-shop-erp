<?php

namespace Modules\Vehicle\src\Listeners;

use Modules\Vehicle\src\Events\ServiceHistoryCreated;
use Modules\Vehicle\src\Events\ServiceHistoryUpdated;
use Modules\Vehicle\src\Events\ServiceHistoryDeleted;
use Modules\Vehicle\src\Notifications\ServiceHistoryCreatedNotification;
use Modules\Vehicle\src\Notifications\ServiceHistoryUpdatedNotification;
use Modules\Vehicle\src\Notifications\ServiceHistoryDeletedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendServiceHistoryNotifications
{
    public function handle($event)
    {
        // Get users with relevant permissions
        $users = User::permission(['view-vehicles'])->get();

        // Determine notification type based on event
        $notification = match(get_class($event)) {
//            ServiceHistoryCreated::class => new ServiceHistoryCreatedNotification($event->serviceHistory),
//            ServiceHistoryUpdated::class => new ServiceHistoryUpdatedNotification($event->serviceHistory),
//            ServiceHistoryDeleted::class => new ServiceHistoryDeletedNotification($event->serviceHistory),
            default => null
        };

        if ($notification) {
            Notification::send($users, $notification);
        }
    }
}
