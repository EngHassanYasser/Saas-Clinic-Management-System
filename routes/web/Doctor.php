<?php

use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\DoctorQueryController;
use Illuminate\Support\Facades\Route;
Route::middleware('auth','tenant.context','verified')->group(function () {

Route::get(
    '/doctors',
    [DoctorController::class, 'index']
)->name('doctors.index');
Route::get(
    '/doctors/create',
    [DoctorController::class, 'create']
)->name('doctors.create');
Route::post(
    '/doctors/store',
    [DoctorController::class, 'store']
)->name('doctors.store');
Route::put(
    '/doctors/{id}',
    [DoctorController::class, 'update']
)->name('doctors.update');
Route::delete(
    '/doctors/{id}',
    [DoctorController::class, 'destroy']
)->name('doctors.destroy');
Route::get(
    '/doctors/clinic/{clinic}/speciality/{speciality}/service/{service}/',
    [DoctorQueryController::class, 'getAvailableDoctors']
)->name('doctors.getAvailableDoctors');
});