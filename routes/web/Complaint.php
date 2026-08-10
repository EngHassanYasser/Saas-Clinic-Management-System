<?php

use App\Http\Controllers\Complaint\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth','verified')->group(function () {

    Route::middleware('tenant.context')->get(
        '/complaintts',
        [ComplaintController::class, 'index']
    )->name('complaintts.index');
    Route::get(
        '/complaintts/create',
        [ComplaintController::class, 'create']
    )->name('complaintts.create');
    Route::get(
        '/complaintts/{complaint}',
        [ComplaintController::class, 'edit']
    )->name('complaintts.edit');
    Route::middleware('tenant.context')->post(
        '/complaintts',
        [ComplaintController::class, 'store']
    )->name('complaintts.store');
    Route::middleware('tenant.context')->put(
        '/complaintts/{complaint}/',
        [ComplaintController::class, 'update']
    )->name('complaintts.update');
    Route::delete(
        '/complaintts/{complaint}/',
        [ComplaintController::class, 'destroy']
    )->name('complaintts.destroy');
});
