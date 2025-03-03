<?php

use Illuminate\Support\Facades\Route;
use Modules\Vehicle\src\Http\Controllers\VehicleViewController;
use Modules\Vehicle\src\Http\Controllers\ServiceHistoryViewController;

Route::middleware(['web', 'auth:sanctum', 'verified'])->group(function () {
    // Vehicle routes
    Route::resource('vehicles', VehicleViewController::class);

    Route::delete('vehicle/files/{file}', [VehicleViewController::class, 'destroyFile'])
        ->name('vehicle.files.destroy');

    // Service History routes (make sure to use explicit names)
    Route::resource('vehicles.service-history', ServiceHistoryViewController::class)
        ->names([
            'index' => 'vehicles.service-history.index',
            'create' => 'vehicles.service-history.create',
            'store' => 'vehicles.service-history.store',
            'show' => 'vehicles.service-history.show',
            'edit' => 'vehicles.service-history.edit',
            'update' => 'vehicles.service-history.update',
            'destroy' => 'vehicles.service-history.destroy',
        ]);

    Route::delete('vehicle/service-history/files/{file}', [ServiceHistoryViewController::class, 'destroyFile'])
        ->name('vehicle.service-history.files.destroy');
});
