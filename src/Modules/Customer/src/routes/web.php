<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\src\Http\Controllers\CustomerViewController;

Route::middleware(['web', 'auth:sanctum', 'verified'])->group(function () {
    Route::resource('customers', CustomerViewController::class);
});
