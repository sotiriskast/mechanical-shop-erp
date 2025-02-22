<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\src\Http\Controllers\V1\CustomerController;

Route::prefix('api')
    ->middleware(['api', 'auth:sanctum', 'api.version'])
    ->group(function () {
        // V1 Routes
        Route::prefix('v1')->group(function () {
            Route::get('customers/search', [CustomerController::class, 'search']);
            Route::apiResource('customers', CustomerController::class);
        });
    });
