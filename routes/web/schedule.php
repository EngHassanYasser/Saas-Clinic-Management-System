<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('schedule/index',[ScheduleController::class,'index'])->name('schedules.index');