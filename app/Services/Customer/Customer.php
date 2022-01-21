<?php

namespace App\Services\Customer;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Customer
{
    /**
     * create new customer or update the existing
     * @param \App\Models\Staycation\Customer $customer
     * @param array $data
     * @return mixed
     */
    public function create(\App\Models\Staycation\Customer $customer, array $data)
    {
        $newCustomer = \App\Models\Staycation\Customer::where('email',$data['email'])->orWhere('mobile_number',$data['mobile_number']);
        if(collect($newCustomer->first())->count() > 0)
        {

            $newCustomer->update($data);
            return $newCustomer->first();
        }
        return $customer::create($data);
    }

    function customerDataTable($customers)
    {
        return DataTables::of($customers)
            ->addColumn('date',function($customer){
                return Carbon::parse($customer->created_at)->format('M-d-Y');
            })
            ->addColumn('fullname',function($customer){
                return ucwords($customer->firstname.' '.$customer->lastname);
            })
            ->editColumn('created_by', function($customer){
                return User::find($customer->created_by)->username;
            })
            ->addColumn('action', function($customers){
                $action = '';
                if(auth()->user()->can('view customer'))
                {
                    $action .= '<a href="'.route('customers.show',['customer' => $customers->id]).'" class="btn btn-xs btn-success view-assigned-staycation-btn" id="'.$customers->id.'" title="View"><i class="fa fa-eye"></i></a> ';
                }
                if(auth()->user()->can('add customer'))
                {
                    $action .= '<a href="'.route('customers.show',['customer' => $customers->id]).'" class="btn btn-xs btn-primary view-assigned-staycation-btn" id="'.$customers->id.'" title="View"><i class="fa fa-calendar-alt"></i></a> ';
                }
                if(auth()->user()->can('delete customer'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-assigned-staycation-btn" id="'.$customers->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * the user must not be an owner
     * retrieve all customers assigned by the agent
     * @param $user
     * @return \Illuminate\Support\Collection
     */
    public function allCustomersAssignedThruStaycations($user)
    {
        $assignedStaycations = collect($user->stayCationLists)->pluck('id');
        return DB::table('customers')
            ->leftJoin('bookings','customers.id','bookings.customer_id')
            ->whereIn('bookings.staycation_id',$assignedStaycations)
            ->groupBy('bookings.customer_id')
            ->get();
    }
}
