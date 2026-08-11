<?php

use App\Http\Controllers\Complaint\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth','role:clinic,patient','verified')->group(function () {

    Route::get(
        '/complaints',
        [ComplaintController::class, 'index']
    )->name('complaints.index');
    
    Route::get(
        '/complaints/create',
        [ComplaintController::class, 'create']
    )->name('complaints.create');

    Route::get(
        '/complaints/{complaint}',
        [ComplaintController::class, 'edit']
    )->name('complaints.edit');

    Route::middleware('tenant.context')->post(
        '/complaints',
        [ComplaintController::class, 'store']
    )->name('complaints.store');

    Route::put(
        '/complaints/{complaint}/',
        [ComplaintController::class, 'update']
    )->name('complaints.update');

    Route::delete(
        '/complaints/{complaint}/',
        [ComplaintController::class, 'destroy']
    )->name('complaints.destroy');
});
