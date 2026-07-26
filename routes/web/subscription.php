<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\subscriptoinController;

Route::middleware('auth')->group(function () {
    Route::get('/subscriptions', [subscriptoinController::class, 'index'])
        ->name('subscriptions.index');
    Route::put('/subscriptions/{subscription}/status/{newStatus}', [subscriptoinController::class, 'changeStatus'])
        ->name('subscriptions.changeStatus');
    Route::put('/subscriptions/renew/{subscription}/', [subscriptoinController::class, 'renew'])
        ->name('subscriptions.renew');
    Route::post('/subscriptions/', [subscriptoinController::class, 'store'])
        ->name('subscriptions.store');
});
