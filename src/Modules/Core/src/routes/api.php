<?php
// src/Modules/Core/routes/api.php

use Illuminate\Support\Facades\Route;
use Modules\Customer\src\Http\Controllers\V1\CustomerController;

// Example module-specific routes (src/Modules/Customer/routes/api.php)
Route::prefix('api')
    ->middleware(['api', 'api.version'])
    ->group(function () {
        // V1 Routes
        Route::prefix('v1')->group(function () {
            Route::apiResource('customers', CustomerController::class);
        });

    });
