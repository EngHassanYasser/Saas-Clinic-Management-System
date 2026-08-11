<?php

use App\Http\Controllers\MedicalService\MedicalServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic', 'verified')->group(function () {

    Route::get(
        '/clinic/services/index',
        [MedicalServiceController::class, 'index']
    )->name('clinicServices.index');
    
    Route::post(
        '/clinic/services',
        [MedicalServiceController::class, 'store']
    )->name('clinic.services.store')
        ->middleware('tenant.context');

    Route::put(
        '/clinic/services/{clinic}',
        [MedicalServiceController::class, 'update']
    )->name('clinic.services.update')
        ->middleware('tenant.context');

    Route::delete(
        '/clinic/services/{clinic}',
        [MedicalServiceController::class, 'destroy']
    )->name('clinic.services.destroy')
        ->middleware('tenant.context');
});
