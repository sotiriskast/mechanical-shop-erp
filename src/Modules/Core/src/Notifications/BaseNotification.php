<?php

namespace Modules\Core\src\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $channels = ['database', 'mail'];

    public function via($notifiable): array
    {
        return $this->channels;
    }
}
