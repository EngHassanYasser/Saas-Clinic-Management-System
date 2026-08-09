<?php

use App\Http\Controllers\Clinic\ClinicController;
use App\http\Controllers\Clinic\ClinicLookupController;
use App\http\Controllers\Clinic\ClinicStatisticsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:clinic','verified')->group(function () {
    Route::get(
        '/clinics/search',
        [ClinicController::class, 'SearchResults']
    )->name('clinics.SearchResults');
    Route::put(
        'clinics/{clinic}',
        [ClinicController::class, 'update']
    )->name('clinics.update');
    Route::post(
        'clinics',
        [ClinicController::class, 'store']
    )->name('clinics.store');
    Route::get(
        'clinics',
        [ClinicController::class, 'index']
    )->name('clinics.index');
    Route::middleware('tenant.context')->get(
        '/clinics/edit',
        [ClinicController::class, 'edit']
    )->name('clinics.edit');
    Route::put(
        '/clinics/{clinic}',
        [ClinicController::class, 'update']
    )->name('clinics.update');
    Route::delete(
        '/clinics/{clinic}/',
        [ClinicController::class, 'destroy']
    )->name('clinics.destroy');
    Route::get(
        '/clinic/stats',
        [ClinicStatisticsController::class, 'getStats']
    )->name('clinic.stats');
    Route::get(
        '/clinic/services/speciality/{speciality}',
        [ClinicLookupController::class, 'getDoctorServicesBySpecialityId']
    )->name('clinic.getDoctorServicesBySpecialityId');
    Route::get(
        '/clinics/speciality/{speciality}/service/{service}',
        [ClinicLookupController::class, 'getAvailableClinics']
    )->name('clinic.speciality');
});
