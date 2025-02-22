<?php
namespace Modules\Customer\src\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Modules\Customer\src\Models\Customer;

class CustomerCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Customer $customer)
    {}

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('customers.notifications.customer_created.title'))
            ->line(trans('customers.notifications.customer_created.body', ['name' => $this->customer->full_name]))
            ->action('View Customer', route('customers.show', $this->customer->id));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'title' => trans('customers.notifications.customer_created.title'),
            'body' => trans('customers.notifications.customer_created.body', ['name' => $this->customer->full_name]),
            'action_url' => route('customers.show', $this->customer->id)
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'customer_id' => $this->customer->id,
            'title' => trans('customers.notifications.customer_created.title'),
            'body' => trans('customers.notifications.customer_created.body', ['name' => $this->customer->full_name]),
            'action_url' => route('customers.show', $this->customer->id)
        ]);
    }
}
