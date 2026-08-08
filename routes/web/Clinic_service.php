<?php

use App\Http\Controllers\ClinicService\ClinicServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic','verified')->group(function () {

    Route::get(
        '/clinic/services/index',
        [ClinicServiceController::class, 'index']
    )->name('clinicServices.index');
    Route::middleware('tenant.context')->post(
        '/clinic/services',
        [ClinicServiceController::class, 'store']
    )->name('clinic.services.store');
    Route::middleware('tenant.context')->put(
        '/clinic/services/{clinic}',
        [ClinicServiceController::class, 'update']
    )->name('clinic.services.update');
    Route::delete(
        '/clinic/services/{clinic}',
        [ClinicServiceController::class, 'destroy']
    )->name('clinic.services.destroy');
});
