<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Settings\PermissionsDataTable;
use App\Services\Settings\RolesUpdateChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission')->only(['index','show','allPermissions']);
        $this->middleware('permission:add permission')->only('store');
        $this->middleware('permission:edit permission')->only('update');
        $this->middleware('permission:delete permission')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('settings.permissions.index',[
            'roles' => Role::where('name','!=','super admin')->get()
        ]);
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
            'permission' => 'required|unique:permissions,name',
        ]);

        if($validation->passes())
        {
            $permission = Permission::create(['name' => $request->permission]);
            if(!empty($request->roles))
            {
                $permission->syncRoles($request->roles);
            }
            return response()->json(['success' => true, 'message' => 'permission successfully added']);
        }
        return response()->json($validation->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param $name
     * @return \Illuminate\Support\Collection
     */
    public function show($name)
    {
        return collect(Permission::findById($name)->roles()->get())->pluck('name');
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
    public function update(Request $request, $id, RolesUpdateChecker $rolesUpdateChecker)
    {
        $change = 0;
        $nameChange = false;
        $validation = Validator::make($request->all(),[
            'permission' => 'required',
        ]);

        if($validation->passes())
        {
            //save the permission first
            $permission = Permission::findById($id);
            $permission->name = $request->permission;
            if($permission->isDirty('name'))
            {
                $nameChange = true;
                $permission->save();
            }



            //remove roles before updating
              $roles = Permission::findById($id)->roles->pluck('name');

            $change =$rolesUpdateChecker->rolesUpdateChecker($request->roles,$roles);



              if($nameChange === true || $change > 0){
                  foreach ($roles as $role){

                      $permission->removeRole($role);
                  }
                  //assign to permission the newly selected roles
                  $permission->assignRole($request->roles);
                  return response()->json(['success' => true, 'message' => 'permission successfully updated', 'change' => $change]);
              }
            return response()->json(['success' => false, 'message' => 'No changes occurred', 'change' => $change]);
        }
        return response()->json($validation->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        if(Permission::destroy($id))
            return response()->json(['success' => true, 'message' => 'permission successfully updated']);
    }

    public function allPermissions(PermissionsDataTable $permissionsDataTable)
    {
        return $permissionsDataTable->permissions();
    }
}
