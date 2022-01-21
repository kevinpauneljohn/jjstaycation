<?php

namespace App\Services\Staycation;

use App\Models\Staycation\Package;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class StaycationPackage
{
    public function packages($query)
    {
        return DataTables::of($query)
            ->editColumn('amount', function($package){
                return number_format($package->amount,2);
            })
            ->addColumn('color', function($package){
                return '<span class="badge" style="background-color:'.$package->color.'">'.$package->color.'</span>';
            })
            ->editColumn('created_by', function($package){
                return User::find($package->created_by)->username;
            })
            ->addColumn('action', function($package){
                $action = '';
                if(auth()->user()->can('view staycation'))
                {
                    $action .= '<a href="'.route('staycations.show',['staycation' => $package->id]).'" class="btn btn-xs btn-success view-staycation-btn" id="'.$package->id.'" title="View"><i class="fa fa-eye"></i></a> ';
                }
                if(auth()->user()->can('edit staycation package'))
                {
                    $action .= '<a class="btn btn-xs btn-primary edit-staycation-package-btn" id="'.$package->id.'" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete staycation package'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-staycation-btn" id="'.$package->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['color','action'])
            ->make(true);
    }

    /**
     * @param $resort_id
     * @return mixed
     */
    public function resortPackages($resort_id)
    {
        if(isset($resort_id)) return response()->json(Package::where('staycation_id',$resort_id)->get());
        return response('resort id was empty',422);
    }
}
