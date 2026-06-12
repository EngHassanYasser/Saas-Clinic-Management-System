<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/doctors/index',[DoctorController::class,'index'])->name('doctors.index');
Route::get('/doctors/create',[DoctorController::class,'create'])->name('doctors.create');
Route::post('/doctors/store',[DoctorController::class,'store'])->name('doctors.store');
