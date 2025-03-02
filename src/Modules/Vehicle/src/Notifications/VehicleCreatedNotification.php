<?php

namespace Modules\Vehicle\src\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Modules\Vehicle\src\Models\Vehicle;

class VehicleCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Vehicle $vehicle)
    {}

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('vehicles.notifications.vehicle_created.title'))
            ->line(trans('vehicles.notifications.vehicle_created.body', [
                'make' => $this->vehicle->make,
                'model' => $this->vehicle->model,
                'year' => $this->vehicle->year
            ]))
            ->action('View Vehicle', route('vehicles.show', $this->vehicle->id));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id,
            'title' => trans('vehicles.notifications.vehicle_created.title'),
            'body' => trans('vehicles.notifications.vehicle_created.body', [
                'make' => $this->vehicle->make,
                'model' => $this->vehicle->model,
                'year' => $this->vehicle->year
            ]),
            'action_url' => route('vehicles.show', $this->vehicle->id)
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'vehicle_id' => $this->vehicle->id,
            'title' => trans('vehicles.notifications.vehicle_created.title'),
            'body' => trans('vehicles.notifications.vehicle_created.body', [
                'make' => $this->vehicle->make,
                'model' => $this->vehicle->model,
                'year' => $this->vehicle->year
            ]),
            'action_url' => route('vehicles.show', $this->vehicle->id)
        ]);
    }
}
