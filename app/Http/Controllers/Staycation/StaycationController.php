<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Http\Requests\StayCationRequest;
use App\Models\Location\PhilippineBarangay;
use App\Models\Location\PhilippineCity;
use App\Models\Location\PhilippineProvince;
use App\Models\Location\PhilippineRegion;
use App\Models\Staycation\Staycation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaycationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:add staycation')->only('store');
        $this->middleware('permission:delete staycation')->only('destroy');
        $this->middleware('permission:edit staycation')->only(['show','edit','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|\Illuminate\Http\Response|object
     */
    public function index()
    {
        return DB::table('philippine_provinces')->where('province_code','=','0349')->first();
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StayCationRequest $request)
    {
        $request->validated();
        $staycation = new Staycation();
        $staycation->owner_id = $request->owner;
        $staycation->name = $request->name;
        $staycation->address = [
            'address_number' => $request->address_number,
            'region' => $request->region,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay
        ];
        $staycation->description = $request->description;
        if($staycation->save())
            return response()->json(['success' => true, 'message' => 'Staycation was successfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $staycation = Staycation::findOrFail($id);
        return view('dashboard.staycations.staycationProfile',compact(['staycation']));
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
     * @param StayCationRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StayCationRequest $request, $id)
    {
        $staycation = Staycation::findOrFail($id);
        $staycation->owner_id = $request->owner;
        $staycation->name = $request->name;
        $staycation->address = [
            'address_number' => $request->address_number,
            'region' => $request->region,
            'province' => $request->province,
            'city' => $request->city,
            'barangay' => $request->barangay,
        ];
        $staycation->description = $request->description;
        if($staycation->isDirty())
        {
            $staycation->save();
            return response()->json(['success' => true, 'message' => 'Staycation was updated!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes occurred!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(Staycation::destroy($id))
            return response()->json(['success' => true, 'message' => 'Staycation was successfully removed!']);
    }

    /**
     * display all staycation of an owner thru datatable
     * @param \App\Services\Staycation\StayCation $stayCation
     * @param $owner
     * @return mixed
     */
    public function allStayCationByOwner(\App\Services\Staycation\StayCation $stayCation, $owner)
    {
        return $stayCation->stayCations(Staycation::where('owner_id',$owner)->get());
    }

    /**
     * @param $id
     * @return array
     */
    public function getStoredInfo($id)
    {
        $stayCation = Staycation::findOrFail($id);
        $provinces = PhilippineProvince::where('region_code','=',$stayCation->address->region)->get();
        $cities = PhilippineCity::where('province_code','=',$stayCation->address->province)->get();
        $barangays = PhilippineBarangay::where('city_municipality_code','=',$stayCation->address->city)->get();
        return compact(['stayCation','provinces','cities','barangays']);
    }
}
