<?php

use App\Http\Controllers\Plan\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth','role:clinic,patient,super_admin')->group(function () {
    Route::get(
        '/plans',
        [PlanController::class, 'index']
    )->name('plans.index');
    Route::post(
        '/plans',
        [PlanController::class, 'store']
    )->name('plans.store');
    Route::put(
        '/plans/{plan}',
        [PlanController::class, 'update']
    )->name('plans.update');
});
