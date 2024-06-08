<?php

namespace App\Services\Staycation;

use App\Models\Location\PhilippineBarangay;
use App\Models\Location\PhilippineCity;
use App\Models\Location\PhilippineProvince;
use App\Models\Location\PhilippineRegion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StayCation
{
    public function stayCations($query)
    {
        return DataTables::of($query)

            ->editColumn('address',function($staycation){
                $address_number = $staycation->address->address_number;
                $province = $staycation->address->province !== null ? PhilippineProvince::where('province_code',$staycation->address->province)->first()->province_description : "";
                $city = $staycation->address->city !== null ? PhilippineCity::where('city_municipality_code',$staycation->address->city)->first()->city_municipality_description.' City' : "";
                $barangay = $staycation->address->barangay !== null ? 'Brgy. '.PhilippineBarangay::where('barangay_code',$staycation->address->barangay)->first()->barangay_description : "";

                return $address_number.', '.$barangay.' '.$city.', '.$province;


            })
            ->editColumn('created_by',function($staycation){
                return User::find($staycation->created_by)->username;
            })
            ->addColumn('action', function($staycation){
                $action = '';
                if(auth()->user()->can('add booking'))
                {
                    $action .= '<a href="'.route('assigned-staycations.show',['assigned_staycation' => $staycation->id]).'" class="btn btn-xs btn-primary view-assigned-staycation-btn" id="'.$staycation->id.'" title="View"><i class="fa fa-calendar-alt"></i></a> ';
                }
                if(auth()->user()->can('view staycation'))
                {
                    $action .= '<a href="'.route('staycations.show',['staycation' => $staycation->id]).'" class="btn btn-xs btn-success view-staycation-btn" id="'.$staycation->id.'" title="View"><i class="fa fa-eye"></i></a> ';
                }
                if(auth()->user()->can('edit staycation'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-staycation-btn" id="'.$staycation->id.'" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete staycation'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-staycation-btn" id="'.$staycation->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['email','action'])
            ->make(true);
    }


    /**
     * retrieve selected staycations
     * @param array|null $id
     * @return string
     */
    public function selected_stayCations(array $id = null)
    {
        return isset($id) ? \App\Models\Staycation\Staycation::whereIn('id',$id)->get() : "";
    }
}
