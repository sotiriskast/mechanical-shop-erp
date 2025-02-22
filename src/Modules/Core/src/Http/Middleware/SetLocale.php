<?php

namespace Modules\Core\src\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Check header
        $locale = $request->header('Accept-Language');

        // Check query string
        if (!$locale) {
            $locale = $request->query('lang');
        }

        // Check session
        if (!$locale) {
            $locale = session('locale');
        }

        // Default to English if no locale found or not supported
        if (!in_array($locale, ['en', 'el', 'ru'])) {
            $locale = 'en';
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
