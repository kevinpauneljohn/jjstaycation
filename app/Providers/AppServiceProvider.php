<?php

namespace App\Providers;

use App\View\Components\Booking\AddBooking;
use App\View\Components\booking\BookingDetails;
use App\View\Components\packages\package;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('add-booking-form',AddBooking::class);
        Blade::component('booking-details-modal',BookingDetails::class);
        Blade::component('package-form',package::class);
    }
}
