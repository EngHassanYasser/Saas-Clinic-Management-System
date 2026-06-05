<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/ads',function(){
    return view('ads.index');
})->name('dashboard.ads');

Route::get('dashboard/clinics-management', function () {
    return view('clinic.index');
})->name('clinics.management');
Route::get('/dashboard/subscriptions', function () {
    return view('subscriptions.index');
})->name('dashboard.subscriptions');
Route::get('/complain/create', function () {
    return view('complain.create');
})->name('complain.create');
Route::get('/vaction-index', function () {
    return view('vacation.index');
})->name('vacation.index');
Route::get('/complain-index', function () {
    return view('complain.index');
})->name('complain.index');
Route::get('/', function () {
    return view('Home');
})->name('home');
Route::get('/doctors-list', function () {
    return view('doctor.index');
})->name('doctor.index');
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});
Route::get('/add-doctor', function () {
    return view('doctor.add');
})->name('doctor.add');
Route::get('/search', function () {
    return view('SearchResults');
})->name('search.results');

Route::get('/showClinic', function () {
    return view('clinic.Show');
})->name('show.clinic');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/my-appointments', function () {
        return view('appointments.My-Appointments');
    })->name('my-appointments');

    Route::get('/appointments-index', function () {
        return view('appointments.index');
    })->name('appointments.index');

    Route::get('/clinic-edite', function () {
        return view('clinic.edite');
    })->name('clinic.edite');
    Route::get('/create-appointment', function () {
        return view('appointments.create');
    })->name('clinic.create');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
