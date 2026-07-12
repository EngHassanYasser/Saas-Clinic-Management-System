<?php

use Illuminate\Support\Facades\Route;

Route::get('/ads/index', function () {
    return view('ads.index');
})->name('ads.index');

Route::get('clinics/index', function () {
    return view('clinics.index');
})->name('clinics.index');

Route::get('/', function () {
    return view('Home');
})->name('home');
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.index');
    })->name('dashboard');
});
Route::get('/clinics/search', function () {
    return view('clinics.SearchResults');
})->name('clinics.SearchResults');
Route::middleware('auth', 'verified')->group(function () {

    Route::get('/clinics/edite', function () {
        return view('clinics.edite');
    })->name('clinics.edite');
});
require __DIR__ . '/web/appointment.php';
require __DIR__ . '/web/doctor.php';
require __DIR__ . '/web/clinic_service.php';
require __DIR__ . '/web/schedule.php';
require __DIR__ . '/web/vication.php';
require __DIR__ . '/web/subscription.php';
require __DIR__ . '/web/complain.php';
require __DIR__ . '/web/profile.php';
require __DIR__ . '/auth.php';
