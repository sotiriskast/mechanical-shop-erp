<?php
namespace Modules\Customer\src\Listeners;

use Modules\Customer\src\Events\CustomerCreated;
use Modules\Customer\src\Notifications\CustomerCreated as CustomerCreatedNotification;
use App\Models\User;

class SendCustomerCreatedNotifications
{
    public function handle(CustomerCreated $event): void
    {
        // Notify all users with 'view-customers' permission
        User::permission('view-customers')->each(function ($user) use ($event) {
            $user->notify(new CustomerCreatedNotification($event->customer));
        });
    }
}
