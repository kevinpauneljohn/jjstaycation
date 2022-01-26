<?php

namespace App\Http\Controllers\Staycation;

use Acaronlex\LaravelCalendar\Calendar;
use App\Http\Controllers\Controller;
use App\Models\Staycation\Booking;
use App\Models\Staycation\Staycation;
use App\Services\Occasion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssignedStayCationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:add booking','only_assigned_resorts'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.assigned-index');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Occasion $occasion, $id)
    {
        $assignedStaycation = auth()->user()->hasRole('super admin') ? Staycation::where('id',$id)->first() : auth()->user()->stayCationLists->where('id',$id)->first();
//        $status = ['pencil book','on-going','extended','completed','cancelled'];
        $status = ['pencil book','reserved'];
        $occasion = $occasion->occasion();
        return view('dashboard.bookings.add-booking',compact('assignedStaycation','status','occasion'));
//        return $assignedStaycation;
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
}
