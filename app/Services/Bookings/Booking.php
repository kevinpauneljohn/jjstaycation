<?php

namespace App\Services\Bookings;

use Carbon\Carbon;

class Booking
{

    /**
     * @param array $bookingData
     * @return mixed
     */
    public function create(array $bookingData)
    {
        return \App\Models\Staycation\Booking::create($bookingData);
    }

    /**
     * get booking details
     * @param $bookingsId
     * @return mixed
     */
    public function getBookingsWithUser($bookingsId)
    {
        return \App\Models\Staycation\Booking::with(['user','customer'])->where('id',$bookingsId)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBookingsByStaycationId($id)
    {
        return \App\Models\Staycation\Booking::where('staycation_id',$id)->get();
    }

    /**
     * @param $stayCationId
     * @return mixed
     */
    public function blockedDates($stayCationId)
    {
        return collect($this->getBookingsByStaycationId($stayCationId))->mapToGroups(function($item, $key){
            return [
                [

                    "start" => Carbon::parse($item['start'])->format('Y-m-d H:i:s'),
                    "end" => Carbon::parse($item['end'])->format('Y-m-d H:i:s'),
                ]
            ];
        })->first();
    }

    /**
     * get all overlapping dates from the selected dates
     * @param $staycation_d
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function overlapping($staycation_d, $start_date, $end_date)
    {
        return \App\Models\Staycation\Booking::where('staycation_id', $staycation_d)
            ->overlapping($start_date, $end_date)->get();
    }
}
