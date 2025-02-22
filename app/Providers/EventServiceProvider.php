<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Add any dashboard-specific events here
        'App\Events\DashboardStatsUpdated' => [
            'App\Listeners\UpdateDashboardCache',
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
