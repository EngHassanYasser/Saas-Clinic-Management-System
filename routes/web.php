<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
})->name('home');

Route::get('/dashboard',function(){
    return view('dashboard.index');
})->name('dashboard');
Route::get('/search', function () {
    return view('SearchResults');
})->name('search.results');

Route::get('/showClinic', function () {
    return view('clinics.Show');
})->name('show.clinic');

Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/my-appointments', function () {
        return view('appointments.My-Appointments');
    })->name('my-appointments');

   Route::get('/appointments-index',function () {
        return view('appointments.index');
   })->name('appointments.index');

   Route::get('/clinic-edite',function() {
    return view('clinics.edite');
   })->name('clinic.edite');
   Route::get('/create-appointment',function(){
    return view('appointments.create');
   })->name('clinic.create');

});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
