<?php

use App\Http\Controllers\ClinicController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/clinics/search', function () {
        return view('clinics.SearchResults');
    })->name('clinics.SearchResults');
    Route::put('clinics/{clinic}', [clinicController::class, 'update'])->name('clinics.update');
    Route::post('clinics', [ClinicController::class, 'store'])->name('clinics.store');
    Route::get('clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::get('/clinics/edit', [clinicController::class, 'edit'])->name('clinics.edit');
});
