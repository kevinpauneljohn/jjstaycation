<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    //bookings//
    Route::resource('bookings',\App\Http\Controllers\Staycation\BookingsController::class);
    Route::post('/bookings/availability',[\App\Http\Controllers\Staycation\BookingsController::class,'checkAvailability'])->name('bookings.date.availability');
    Route::get('/booking-details/{booking}',[\App\Http\Controllers\Staycation\BookingsController::class,'getBookings'])->name('bookings.details');
    Route::get('/blocked-dates/{staycation}',[\App\Http\Controllers\Staycation\BookingsController::class,'blockedDates'])->name('bookings.blocked.dates');
    //end bookings//
    Route::get('/booking-lists',[\App\Http\Controllers\Staycation\BookingsController::class,'getBookingsLists'])->name('get.bookings.lists');
});
