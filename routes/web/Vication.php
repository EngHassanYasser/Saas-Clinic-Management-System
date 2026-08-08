<?php

use App\Http\Controllers\Vacation\VacationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('/vications', [VacationController::class, 'index'])->name('vications.index');
        Route::post('/vications', [VacationController::class, 'store'])->name('vications.store');
        Route::put('/vications/{vication}', [VacationController::class, 'update'])->name('vications.update');
        Route::delete('/vications/{vication}', [VacationController::class, 'destroy'])->name('vications.destroy');
    });

});
