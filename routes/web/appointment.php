<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'changeStatus'])
        ->name('appointments.changeStatus-status');
    Route::get('appointments/AvailableAppointments/clinic/{clinic}/doctor/{doctor}/visitDate/{visit_date}/', [AppointmentController::class, 'getAvailableAppointments'])
        ->name('appointments.getAvailableSlots');
    Route::patch('appointments/{appointmentId}/{visit_date}/{slot}/availableSlots', [AppointmentController::class, 'reschdule'])
        ->name('appointments.reschdule');
});
