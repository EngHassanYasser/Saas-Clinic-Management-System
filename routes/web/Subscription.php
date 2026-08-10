<?php

use App\Http\Controllers\Subscription\EnSubscriptionStatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Subscription\SubscriptoinController;
Route::middleware('auth','role:clinic','verified')->group(function () {

Route::middleware('auth')->group(function () {
    Route::get(
        '/subscriptions',
        [SubscriptoinController::class, 'index']
    )->name('subscriptions.index');
    Route::put(
        '/subscriptions/{subscription}/status/{newStatus}',
        [EnSubscriptionStatusController::class, 'changeStatus']
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
});