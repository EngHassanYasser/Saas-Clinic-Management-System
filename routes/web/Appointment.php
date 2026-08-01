<?php

use App\Http\Controllers\Appointment\AppointmentAvailabilityController;
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\AppointmentRescheduleController;
use App\Http\Controllers\Appointment\AppointmentStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get(
        'appointments',
        [AppointmentController::class, 'index']
    )->name('appointments.index');
    Route::post(
        'appointments',
        [AppointmentController::class, 'store']
    )->name('appointments.store');
    Route::get(
        'appointments/create',
        [AppointmentController::class, 'create']
    )->name('appointments.create');
    Route::patch(
        '/appointments/{appointment}/status',
        [AppointmentStatusController::class, 'changeStatus']
    )->name('appointments.changeStatus-status');
    Route::get(
        'appointments/AvailableAppointments/clinic/{clinic}/doctor/{doctor}/visitDate/{visit_date}/',
        [AppointmentAvailabilityController::class, 'getAvailableAppointments']
    )->name('appointments.getAvailableAppointments');
    Route::patch(
        'appointments/{appointmentId}/{visit_date}/{slot}/availableSlots',
        [AppointmentRescheduleController::class, 'reschdule']
    )->name('appointments.reschdule');
});
