<?php

use App\Http\Controllers\Subscription\SubscriptionStatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Subscription\SubscriptoinController;

Route::middleware('auth')->group(function () {
    Route::get(
        '/subscriptions',
        [SubscriptoinController::class, 'index']
    )->name('subscriptions.index');
    Route::put(
        '/subscriptions/{subscription}/status/{newStatus}',
        [SubscriptionStatusController::class, 'changeStatus']
    )->name('subscriptions.changeStatus');
    Route::put(
        '/subscriptions/renew/{subscription}/',
        [SubscriptoinController::class, 'renew']
    )->name('subscriptions.renew');
    Route::post(
        '/subscriptions/',
        [SubscriptoinController::class, 'store']
    )->name('subscriptions.store');
});
