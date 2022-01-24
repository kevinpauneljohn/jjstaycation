<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Staycation\Booking;
use App\Models\Staycation\Package;
use App\Services\Customer\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view booking','only_assigned_resorts'])->only(['show','blockedDates']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * @param BookingRequest $request
     * @param Customer $customer
     * @param \App\Services\Bookings\Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingRequest $request, Customer $customer, \App\Services\Bookings\Booking $booking)
    {
        $date = explode("-",$request->preferred_date);
        $start_date = Carbon::parse($date[0]);
        $end_date = Carbon::parse($date[1]);

        //this will check if preferred date overlaps with another bookings
        if(collect($booking->overlapping($request->staycation_id, $start_date, $end_date))->count() > 0)
        {
            return response()->json(['success' => false,'date' => false, 'message' => 'Selected date overlaps from an existing bookings <br/>Please select another date',
                'errors' => ['preferred_date' => ['Date overlaps from another bookings']],
            ],422);

        }

        $data = [
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'facebook_url' => $request->facebook_url,
        ];
        //save the customer first before saving the bookings
        if($getCustomer =  $customer->create(new \App\Models\Staycation\Customer(), $data))
        {
//            $date = explode("-",$request->preferred_date);
            $packageDetails = Package::find($request->package);

            if($request->package !== "custom")
            {
                $package = $packageDetails->name;
                $diffDays = $packageDetails->days;
                $color = $packageDetails->color;
            }else{
                $package = "Custom";
                $date1 = Carbon::parse($date[0]);
                $diffDays = $date1->diffInDays($date[1]);
                $color = null;
            }

            //check if start date is greater than end date
            $startDate = Carbon::parse($date[0]);
            $endDate = Carbon::parse($date[1]);
            if($startDate->gt($endDate))
                return response()->json(['success' => false,'date' => false, 'message' => 'Start date must not be greater than the end date',
                    'errors' => ['preferred_date' => ['Start date must not be greater than the end date']],
                ],422);

            $bookingDetails = [
                'title' => $package,
                'customer_id' => $getCustomer->id,
                'staycation_id' => $request->staycation_id,
                'total_amount' => str_replace(',','',$request->total_amount),
                'start' => $date[0],
                'end' => $date[1],
                'pax' => $request->pax,
                'remarks' => $request->remarks,
                'occasion' => $request->occasion,
                'status' => $request->status,
                'isAllDay' => $diffDays >= 1,
                'backgroundColor' => $color
            ];

            if($booking->create($bookingDetails))
            {
                return response()->json(['success' => true, 'message' => 'Booking successfully added!']);
            }
            return response()->json(['success' => false, 'message' => 'Booking was not saved!']);
        }
        return response()->json(['success' => false, 'message' => 'Customer was not added!']);

    }

    /**
     * Get all the resort or staycation bookings.
     *
     * @param \App\Services\Bookings\Booking $booking
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function show(\App\Services\Bookings\Booking $booking, $id)
    {
        return collect($booking->getBookingsByStaycationId($id))->mapToGroups(function($item, $key){
            return [
                [
                    "id" => $item['id'],
                    "title" => $item['title'].' Package / Guest: '.\App\Models\Staycation\Customer::find($item['customer_id'])->full_name,
//                    "allDay" => $item['isAllDay'],
                    "start" => Carbon::parse($item['start'])->format('Y-m-d H:i:s'),
                    "end" => Carbon::parse($item['end'])->format('Y-m-d H:i:s'),
                    "color" => $item['status'] !== "pencil book" ? $item['backgroundColor'] : "#7d7a7a",
//                    'url' => 'hello'
                ],
            ];
        })->first();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @return mixed
     */
    public function blockedDates(\App\Services\Bookings\Booking $booking, $id)
    {
        return $booking->blockedDates($id);
    }

    public function getBookings(\App\Services\Bookings\Booking $booking, $bookingsId)
    {
        return $booking->getBookingsWithUser($bookingsId);
    }
}
