<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\subscriptoinController;

Route::get('/subscriptions', [subscriptoinController::class, 'index'])->name('subscriptions.index');
