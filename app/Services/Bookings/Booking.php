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
        $collection = collect(\App\Models\Staycation\Booking::with(['user','customer'])->where('id',$bookingsId)->first());

//        $collection = collect(["one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5]);

        $multiplied = $collection->map(function ($item, $key) {
            if($key == "start"){
                $item  = Carbon::parse($item)->format('Y-m-d H:i:s');
            }
            if($key == "end"){
                $item  = Carbon::parse($item)->format('Y-m-d H:i:s');
            }
            return $item;
        });

        return $multiplied->all();
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
     * @param $staycation_id
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function overlapping($staycation_id, $start_date, $end_date)
    {
        return \App\Models\Staycation\Booking::where('staycation_id', $staycation_id)
            ->overlapping($start_date, $end_date)->get();
    }

    public function overlappingExceptBooking($staycation_id, $bookingId, $start_date, $end_date)
    {
        return \App\Models\Staycation\Booking::where('staycation_id', $staycation_id)
            ->whereNotIn('id',[$bookingId])
            ->overlapping($start_date, $end_date)->get();
    }
}
