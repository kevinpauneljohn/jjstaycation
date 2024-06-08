<?php

namespace App\Services\Customer;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class Customer
{
    public function __construct()
    {
        $this->viewCustomerPermission()
            ->addCustomerPermission()
            ->editCustomerPermission()
            ->deleteCustomerPermission();
    }
    private function viewCustomerPermission()
    {
        if(Permission::where('name','view customer')->count() === 0)
        {
            Permission::create(['name' => 'view customer'])->assignRole('admin','supervisor','manager','agent','owner','care taker');
        }
        return $this;
    }

    private function addCustomerPermission()
    {
        if(Permission::where('name','add customer')->count() === 0)
        {
            Permission::create(['name' => 'add customer'])->assignRole('admin','supervisor','manager','agent','owner');
        }
        return $this;
    }
    private function editCustomerPermission()
    {
        if(Permission::where('name','edit customer')->count() === 0)
        {
            Permission::create(['name' => 'edit customer'])->assignRole('admin','supervisor','manager','agent','owner');
        }
        return $this;
    }

    private function deleteCustomerPermission()
    {
        if(Permission::where('name','delete customer')->count() === 0)
        {
            Permission::create(['name' => 'delete customer'])->assignRole('admin','supervisor','manager','owner');
        }
    }
    /**
     * create new customer or update the existing
     * @param \App\Models\Staycation\Customer $customer
     * @param array $data
     * @return mixed
     */
    public function create(\App\Models\Staycation\Customer $customer, array $data)
    {
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

    public function getCustomer($customerId)
    {
        return \App\Models\Staycation\Customer::findOrFail($customerId);
    }

    public function updateCustomer($customerId, array $data): bool
    {
        $customer = $this->getCustomer($customerId);
        $customer->fill($data);
        if($customer->isDirty())
        {
            return (bool)$customer->save();
        }
        return false;
    }
}
