<?php

use App\Http\Controllers\ComplainController;
use Illuminate\Support\Facades\Route;

Route::get('/complains', [ComplainController::class, 'index'])->name('complains.index');
Route::get('/complain/create', function () {
    return view('complains.create');
})->name('complains.create');
