<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::patch('dashboard/maintenance-mode', [DashboardController::class, 'updateMaintenanceMode'])
        ->name('dashboard.maintenance-mode.update');

    Route::resource('dashboard', DashboardController::class);
});
