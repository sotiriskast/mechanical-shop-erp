<?php

namespace Modules\Core\src\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ApiVersion
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get version from header or default to v1
        $version = $request->header('Accept-Version', 'v1');

        // Validate version format
        if (!preg_match('/^v[1-9]\d*$/', $version)) {
            throw new BadRequestHttpException('Invalid API version format');
        }

        // Store version in request for later use
        $request->merge(['api_version' => $version]);

        return $next($request);
    }
}
