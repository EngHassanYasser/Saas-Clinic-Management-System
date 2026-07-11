<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/ads/index', function () {
    return view('ads.index');
})->name('ads.index');

Route::get('clinics/index', function () {
    return view('clinics.index');
})->name('clinics.index');
Route::get('/dashboard/subscriptions', function () {
return view('subscriptions.index', [
    'subscriptions' => [
        [
            'id' => 1,
            'clinic' => 'عيادة النور',
            'plan' => 'premium',
            'price' => 500,
            'start' => '2026-01-01',
            'end' => '2027-01-01',
        ],
        [
            'id' => 2,
            'clinic' => 'عيادة الشفاء',
            'plan' => 'basic',
            'price' => 200,
            'start' => '2025-01-01',
            'end' => '2026-01-01',
        ],
        [
            'id' => 3,
            'clinic' => 'عيادة الرحمة',
            'plan' => 'enterprise',
            'price' => 900,
            'start' => '2026-05-01',
            'end' => '2027-05-01',
        ],
        [
            'id' => 4,
            'clinic' => 'عيادة الحياة',
            'plan' => 'premium',
            'price' => 650,
            'start' => '2025-06-01',
            'end' => '2025-12-20',
        ],
    ]
]);
})->name('subscriptions.index');
Route::get('/complain/create', function () {
    return view('complains.create');
})->name('complains.create');
Route::get('/vactions/ndex', function () {
    return view('vacations.index');
})->name('vacations.index');
Route::get('/complains/index', function () {
    return view('complains.index');
})->name('complains.index');
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
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/web/appointment.php';
require __DIR__ . '/web/doctor.php';
require __DIR__ . '/web/clinic_service.php';
require __DIR__ . '/web/schedule.php';
require __DIR__ . '/web/vication.php';
require __DIR__ . '/auth.php';
