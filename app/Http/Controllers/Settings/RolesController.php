<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Settings\RolesDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role')->only(['index','allRoles']);
        $this->middleware('permission:add role')->only('store');
        $this->middleware('permission:edit role')->only('update');
        $this->middleware('permission:delete role')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.roles.index');
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
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'role' => 'required|unique:roles,name'
        ]);

        if($validation->passes())
        {
            Role::create(['name' => $request->role]);
            return response()->json(['success' => true, 'message' => 'Role successfully added!']);
        }
        return response()->json($validation->errors());
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'role' => 'required|unique:roles,name'
        ]);

        if($validation->passes())
        {
            $role = Role::findById($id);
            $role->name = $request->role;
            if($role->isDirty())
            {
                $role->save();
                return response()->json(['success' => true, 'message' => 'Role successfully updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred!']);
        }
        return response()->json($validation->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(Role::destroy($id))
            return response()->json(['success' => true, 'message' => 'Role successfully removed']);
    }

    /**
     * Diplay roles data table
     * @param RolesDataTable $rolesDataTable
     * @return mixed
     */
    public function allRoles(RolesDataTable $rolesDataTable)
    {
        return $rolesDataTable->roles();
    }
}
