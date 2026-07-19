<?php

use App\Http\Controllers\ClinicServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/clinic/services/index', [ClinicServiceController::class, 'index'])
        ->name('clinicServices.index');
    Route::post('/clinic/services', [ClinicServiceController::class, 'store'])
        ->name('clinic.services.store');
    Route::put('/clinic/services/{id}', [ClinicServiceController::class, 'update'])
        ->name('clinic.services.update');
    Route::delete('/clinic/services/{id}', [ClinicServiceController::class, 'destroy'])
        ->name('clinic.services.destroy');
});
