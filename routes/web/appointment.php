<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
Route::get('appointments',[AppointmentController::class,'index'])->name('appointments.index');
});