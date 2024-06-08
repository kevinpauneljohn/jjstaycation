<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Staycation\Staycation;
use App\Services\Customer\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view customer')->only(['allCustomers']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.customers.index');
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
    public function edit($id, Customer $customer)
    {
        return $customer->getCustomer($id);
    }


    public function update(UpdateCustomerRequest $request, $id, Customer $customer): \Illuminate\Http\JsonResponse
    {
        return $customer->updateCustomer($id, collect($request->all())->toArray()) ?
            response()->json(['success' => true, 'message' => 'Customer details updated!']) :
            response()->json(['success' => false, 'message' => 'No changes made!']) ;
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

    public function allCustomers(Customer $customer)
    {
        return $customer->customerDataTable($customer->allCustomersAssignedThruStaycations(auth()->user()));
    }
}
