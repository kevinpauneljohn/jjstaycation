<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    //customer//
    Route::resource('customers',\App\Http\Controllers\Staycation\CustomerController::class);
    Route::get('/all-customers',[\App\Http\Controllers\Staycation\CustomerController::class,'allCustomers'])->name('all.customers.list');
    //end customer //
});
