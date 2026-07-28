<?php

use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/plans', [PlanController::class, 'index'])
        ->name('plans.index');
    Route::post('/plans', [PlanController::class, 'store'])
        ->name('plans.store');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])
        ->name('plans.update');
    Route::put('/plans/plan/{plan}/newStatus/{status}', [PlanController::class, 'changeStatus'])
        ->name('plans.changeStatus');
});
