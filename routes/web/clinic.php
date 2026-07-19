<?php

use App\Http\Controllers\ClinicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/clinics/search', function () {
        return view('clinics.SearchResults');
    })->name('clinics.SearchResults');
    Route::middleware('auth', 'verified')->group(function () {

        Route::get('/clinics/edit', function () {
            return view('clinics.edite');
        })->name('clinics.edit');
    });
    Route::get('clinics', [ClinicController::class, 'index'])->name('clinics.index');
});
