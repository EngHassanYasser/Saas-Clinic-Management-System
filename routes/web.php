<?php

use Illuminate\Support\Facades\Route;

Route::get('/ads/index', function () {
    return view('ads.index');
})->name('ads.index');

Route::get('/', function () {
    return view('Home');
})->name('home');
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.index');
    })->name('dashboard');
});

require __DIR__ . '/web/appointment.php';
require __DIR__ . '/web/doctor.php';
require __DIR__ . '/web/clinic_service.php';
require __DIR__ . '/web/schedule.php';
require __DIR__ . '/web/vication.php';
require __DIR__ . '/web/subscription.php';
require __DIR__ . '/web/complain.php';
require __DIR__ . '/web/clinic.php';
require __DIR__ . '/web/profile.php';
require __DIR__ . '/auth.php';
