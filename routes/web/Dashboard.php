<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DashboardStatisticsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth','role:clinic,patient,super_admin')->group(function () {
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard.index');
    Route::get(
        '/stats',
        [DashboardStatisticsController::class, 'getstats']
    )->name('dashboard.getstats');
});
