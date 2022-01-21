<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Models\Staycation\Package;
use App\Models\Staycation\Staycation;
use App\Services\Staycation\StaycationPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view staycation package')->only(['allPackages','index','show']);
        $this->middleware('permission:add staycation package')->only(['store']);
        $this->middleware('permission:edit staycation package')->only(['edit','update']);
        $this->middleware('permission:delete staycation package')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return is_numeric('34');
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
    public function store(PackageRequest $request)
    {
        $package = new Package();
        $package->staycation_id = $request->staycation;
        $package->name = $request->name;
        $package->pax = $request->pax;
        $package->amount = $request->amount;
        $package->days = $request->days;
        $package->time_in = $request->time_in;
        $package->time_out = $request->time_out;
        $package->remarks = $request->remarks;
        $package->color = $request->color;
        if($package->save())
            return response()->json(['success' => true, 'message' => 'Package was successfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Package::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PackageRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->name = $request->name;
        $package->remarks = $request->remarks;
        $package->pax = $request->pax;
        $package->amount = $request->amount;
        $package->days = $request->days;
        $package->time_in = $request->time_in;
        $package->time_out = $request->time_out;
        $package->color = $request->color;
        if($package->isDirty())
        {
            $package->save();
            return response()->json(['success' => true, 'message' => 'Package was successfully updated!']);
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
        if(Package::destroy($id))
            return response()->json(['success' => true, 'message' => 'Package successfully removed!']);
    }

    /**
     * @param StaycationPackage $package
     * @param $packageId
     * @return mixed
     */
    public function allPackages(StaycationPackage $package, $staycationId)
    {
        return $package->packages(Package::where('staycation_id',$staycationId)->get());
    }

    public function getPackageById($id)
    {
        return Package::find($id);
    }
}
