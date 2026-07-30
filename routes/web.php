<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
})->name('home');
Route::get('/test-controller', function () {
    return (new ReflectionClass(\App\Http\Controllers\Auth\AuthenticatedSessionController::class))
        ->getFileName();
});
require __DIR__ . '/web/appointment.php';
require __DIR__ . '/web/doctor.php';
require __DIR__ . '/web/clinic_service.php';
require __DIR__ . '/web/schedule.php';
require __DIR__ . '/web/vication.php';
require __DIR__ . '/web/subscription.php';
require __DIR__ . '/web/complain.php';
require __DIR__ . '/web/clinic.php';
require __DIR__ . '/web/add.php';
require __DIR__ . '/web/dashboard.php';
require __DIR__ . '/web/plan.php';
require __DIR__ . '/web/profile.php';
require __DIR__ . '/auth.php';
