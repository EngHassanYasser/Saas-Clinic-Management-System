<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
})->name('home');
require __DIR__ . '/web/Appointment.php';
require __DIR__ . '/web/Doctor.php';
require __DIR__ . '/web/Clinic_service.php';
require __DIR__ . '/web/Schedule.php';
require __DIR__ . '/web/Vication.php';
require __DIR__ . '/web/Subscription.php';
require __DIR__ . '/web/Complain.php';
require __DIR__ . '/web/Clinic.php';
require __DIR__ . '/web/Add.php';
require __DIR__ . '/web/Dashboard.php';
require __DIR__ . '/web/Plan.php';
require __DIR__ . '/web/Profile.php';
require __DIR__ . '/auth.php';
