<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.index');
    })->name('dashboard');
});
