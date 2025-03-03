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

            // Use a different naming convention for API routes
            Route::apiResource('vehicles', VehicleController::class)
                ->names([
                    'index' => 'api.vehicles.index',
                    'store' => 'api.vehicles.store',
                    'show' => 'api.vehicles.show',
                    'update' => 'api.vehicles.update',
                    'destroy' => 'api.vehicles.destroy',
                ]);

            Route::apiResource('vehicles.service-history', ServiceHistoryController::class)
                ->names([
                    'index' => 'api.vehicles.service-history.index',
                    'store' => 'api.vehicles.service-history.store',
                    'show' => 'api.vehicles.service-history.show',
                    'update' => 'api.vehicles.service-history.update',
                    'destroy' => 'api.vehicles.service-history.destroy',
                ]);
        });
    });
