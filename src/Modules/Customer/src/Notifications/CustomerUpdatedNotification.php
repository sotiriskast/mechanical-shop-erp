<?php

namespace Modules\Customer\src\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class CustomerUpdatedNotification extends CustomerCreatedNotification
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('customers.notifications.customer_updated.title'))
            ->line(trans('customers.notifications.customer_updated.body', ['name' => $this->customer->full_name]))
            ->action('View Customer', route('customers.show', $this->customer->id));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'title' => trans('customers.notifications.customer_updated.title'),
            'body' => trans('customers.notifications.customer_updated.body', ['name' => $this->customer->full_name]),
            'action_url' => route('customers.show', $this->customer->id)
        ];
    }
}
