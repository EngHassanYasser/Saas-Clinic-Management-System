<?php

use App\Http\Controllers\Vacation\VacationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic', 'tenant.context', 'verified')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('/vacations',
            [VacationController::class, 'index'])
            ->name('vacations.index');

        Route::post('/vacations',
            [VacationController::class, 'store'])
            ->name('vacations.store');

        Route::put('/vacations/{vacation}',
            [VacationController::class, 'update'])
            ->name('vacations.update');

        Route::delete('/vacations/{vacation}',
            [VacationController::class, 'destroy'])
            ->name('vacations.destroy');
    });

});
