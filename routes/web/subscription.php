<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\subscriptoinController;

Route::get('/dashboard/subscriptions',[subscriptoinController::class, 'index'])->name('subscriptions.index');
