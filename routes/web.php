<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/ads',function(){
    return view('ads.index');
})->name('dashboard.ads');

Route::get('dashboard/clinics-management', function () {
    return view('clinics.index');
})->name('clinics.management');
Route::get('/dashboard/subscriptions', function () {
    return view('subscriptions.index');
})->name('dashboard.subscriptions');
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
Route::get('/doctors/index', function () {
    return view('doctors.index');
})->name('doctors.index');
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.index');
    })->name('dashboard');
});
Route::get('/doctors/add', function () {
    return view('doctors.add');
})->name('doctors.add');
Route::get('/clinics/search', function () {
    return view('clinics.SearchResults');
})->name('clinics.SearchResults');

Route::get('/appointments/create', function () {
    return view('appointments.create');
})->name('appointments.create');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/my-appointments', function () {
        return view('appointment.My-Appointments');
    })->name('my-appointments');

    Route::get('/appointments/index', function () {
        return view('appointment.index');
    })->name('appointments.index');

    Route::get('/clinics/edite', function () {
        return view('clinics.edite');
    })->name('clinics.edite');
    Route::get('/appointments/create', function () {
        return view('appointments.create');
    })->name('clinics.create');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
