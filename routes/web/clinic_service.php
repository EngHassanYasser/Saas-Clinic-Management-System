<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {

Route::get('/clinic/services/index',function(){
    return view('Services.index');
})->name('clinicServices.index');
});