<?php

namespace App\View\Components\Booking;

use Illuminate\View\Component;

class EditBookingDetails extends Component
{

    public $staycation;

    public $status;

    public $occasion;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($staycation, $status, $occasion)
    {
        $this->staycation = $staycation;
        $this->status = $status;
        $this->occasion = $occasion;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.booking.edit-booking-details');
    }
}
