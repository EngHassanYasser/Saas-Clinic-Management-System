<?php

use App\Http\Controllers\Schedule\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get(
    'schedules',
    [ScheduleController::class, 'index']
)->name('schedules.index');
Route::post(
    'schedules/',
    [ScheduleController::class, 'store']
)->name('schedules.store');
Route::put(
    'schedules/{schedule}',
    [ScheduleController::class, 'update']
)->name('schedules.update');
Route::delete(
    'schedules/{schedule}',
    [ScheduleController::class, 'destroy']
)->name('schedules.destroy');
