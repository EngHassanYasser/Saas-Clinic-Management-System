<?php

use App\Http\Controllers\vicationController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function() {
    Route::get('/vications',[vicationController::class,'index'])->name('vications.index');
    Route::post('/vications',[vicationController::class,'store'])->name('vications.store');
    Route::post('/vications/{vication}',[vicationController::class,'update'])->name('vications.update');
});