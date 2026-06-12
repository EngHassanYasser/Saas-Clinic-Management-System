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
    return view('subscriptions.index');
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
Route::get('/schedule/index', function () {
    return view('schedules.index');
})->name('schedules.index');
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/appointments/index', function () {
        return view('appointments.index');
    })->name('appointments.index');

    Route::get('/clinics/edite', function () {
        return view('clinics.edite');
    })->name('clinics.edite');
    Route::get('/appointments/create', function () {
        return view('appointments.create');
    })->name('clinics.create');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
