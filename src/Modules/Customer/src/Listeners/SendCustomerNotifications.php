<?php

namespace Modules\Customer\src\Listeners;

use Modules\Customer\src\Events\CustomerCreated;
use Modules\Customer\src\Events\CustomerUpdated;
use Modules\Customer\src\Events\CustomerDeleted;
use Modules\Customer\src\Notifications\CustomerCreatedNotification;
use Modules\Customer\src\Notifications\CustomerUpdatedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendCustomerNotifications
{
    public function handle($event)
    {
        // Get users with relevant permissions
        $users = User::permission(['view-customers'])->get();

        // Determine notification type based on event
        $notification = match(get_class($event)) {
            CustomerCreated::class => new CustomerCreatedNotification($event->customer),
            CustomerUpdated::class => new CustomerUpdatedNotification($event->customer),
            default => null
        };

        if ($notification) {
            Notification::send($users, $notification);
        }
    }
}
