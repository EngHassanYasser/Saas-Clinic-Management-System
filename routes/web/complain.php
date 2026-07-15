<?php

use App\Http\Controllers\ComplainController;
use Illuminate\Support\Facades\Route;

Route::get('/complains', [ComplainController::class, 'index'])->name('complains.index');
Route::get('/complains/create',[ComplainController::class,'create'])->name('complains.create');
Route::get('/complains/{complain}',[ComplainController::class,'edit'])->name('complains.edit');
Route::post('/complains',[ComplainController::class,'store'])->name('complains.store');
Route::put('/complains/{complain}/',[ComplainController::class,'update'])->name('complains.update');
Route::delete('/complains/{complain}/',[ComplainController::class,'destroy'])->name('complains.destroy');