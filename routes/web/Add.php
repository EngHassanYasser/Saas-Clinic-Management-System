<?php

use Illuminate\Support\Facades\Route;


Route::get('/ads/index', function () {
    return view('ads.index');
})->name('ads.index');
