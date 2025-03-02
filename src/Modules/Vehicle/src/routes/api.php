<?php

use Illuminate\Support\Facades\Route;
use Modules\Vehicle\src\Http\Controllers\V1\VehicleController;
use Modules\Vehicle\src\Http\Controllers\V1\ServiceHistoryController;

Route::prefix('api')
    ->middleware(['api', 'auth:sanctum', 'api.version'])
    ->group(function () {
        // V1 Routes
        Route::prefix('v1')->group(function () {
            Route::get('vehicles/search', [VehicleController::class, 'search']);
            Route::apiResource('vehicles', VehicleController::class);
            Route::apiResource('vehicles.service-history', ServiceHistoryController::class);
        });
    });
