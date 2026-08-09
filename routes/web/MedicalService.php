<?php

use App\Http\Controllers\DoctorService\MedicalServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic','verified')->group(function () {

    Route::get(
        '/clinic/services/index',
        [MedicalServiceController::class, 'index']
    )->name('clinicServices.index');
    Route::middleware('tenant.context')->post(
        '/clinic/services',
        [MedicalServiceController::class, 'store']
    )->name('clinic.services.store');
    Route::middleware('tenant.context')->put(
        '/clinic/services/{clinic}',
        [MedicalServiceController::class, 'update']
    )->name('clinic.services.update');
    Route::delete(
        '/clinic/services/{clinic}',
        [MedicalServiceController::class, 'destroy']
    )->name('clinic.services.destroy');
});
