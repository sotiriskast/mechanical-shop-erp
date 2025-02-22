<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user' => fn () => $request->user()
                ? array_merge($request->user()->toArray(), [
                    'can' => $request->user()->getAllPermissions()->pluck('name'),
                    'notifications' => $request->user()->notifications()
                        ->latest()
                        ->take(5)
                        ->get(),
                    'unread_notifications_count' => $request->user()
                        ->unreadNotifications()
                        ->count(),
                ])
                : null,
        ]);
    }
}
