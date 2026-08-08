<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'role:super_admin')->group(function () {

    Route::get('/ads/index', function () {
        return view('ads.index');
    })->name('ads.index');
});
