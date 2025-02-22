<?php

namespace Modules\Customer\src\Notifications;

use Modules\Core\src\Notifications\BaseNotification;
use Modules\Customer\src\Models\Customer;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerCreated extends BaseNotification
{
    public function __construct(
        protected Customer $customer
    ) {}

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Customer Created')
            ->line('A new customer has been created:')
            ->line("Name: {$this->customer->full_name}")
            ->line("Email: {$this->customer->email}")
            ->action('View Customer', route('customers.show', $this->customer->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'message' => "New customer {$this->customer->full_name} has been created",
            'action_url' => route('customers.show', $this->customer->id),
        ];
    }
}
