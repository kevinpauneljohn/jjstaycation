<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Staycation\Staycation;
use App\Models\User;
use App\Services\Settings\RolesUpdateChecker;
use App\Services\UserDataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user')->only(['index']);
        $this->middleware('permission:add user')->only(['store']);
        $this->middleware('permission:edit user')->only(['edit','update']);
        $this->middleware('permission:assign staycation')->only(['assign_stayCation']);
        $this->middleware('permission:remove assigned staycation')->only(['remove_assigned_staycation']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return DataTableCollectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roles = Role::whereNotIn('name',['super admin','owner','care taker'])->get();
        return view('users.index',compact('roles'));
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
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->date_of_birth = $request->date_of_birth;
        $user->address = $request->address;
        $user->assignRole($request->roles);
        $user->save();
            return response()->json(['success' => true, 'message' => 'Customer successfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $staycations = Staycation::all();
        $assignedStayCations = collect($user->stayCationLists)->pluck('id');
        return view('users.userProfile',compact('user','staycations','assignedStayCations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $user = User::find($id);
        $user = collect($user)->merge(['roles' => $user->getRoleNames()])->merge(['date_of_birth' => isset($user->date_of_birth) ? $user->date_of_birth->format('Y-m-d') : ""]);
        return response()->json($user->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditUserRequest $request, $id, RolesUpdateChecker $rolesUpdateChecker)
    {
        $userChange = false;
        $user = User::find($id);
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->date_of_birth = $request->date_of_birth;
        if($user->isDirty())
        {
            $userChange = true;
            $user->save();
        }

        $roles = $user->getRoleNames();
        $change = $rolesUpdateChecker->rolesUpdateChecker($request->roles, $roles);
        if($userChange === true || $change > 0){
            foreach ($roles as $role){

                $user->removeRole($role);
            }
            //assign to user the newly selected roles
            $user->assignRole($request->roles);
            return response()->json(['success' => true, 'message' => 'Customer successfully updated', 'change' => $change]);
        }
        return response()->json(['success' => false, 'message' => 'no changes occurred', 'change' => $change]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(User::destroy($id))
            return response()->json(['success' => true, 'message' => 'Customer was successfully removed!']);
    }

    public function allUsers(UserDataTable $userDataTable)
    {
        $users = User::whereHas('roles',function($query){
            $query->whereNotIn('name',['owner','care taker','super admin']);
        })->get();
        return $userDataTable->users($users);
    }

    /**
     * @param \App\Services\Staycation\StayCation $stayCation
     * @param Request $request
     * @return array|string
     */
    public function get_staycation_details(\App\Services\Staycation\StayCation $stayCation, Request $request)
    {
        if($request->id !== null){
            $collection = collect($stayCation->selected_stayCations($request->id));

            return $collection->map(function ($item, $key) {
                return collect($item)->merge(['full_address' => $item->full_address]);
            })->all();
        }
        return "";
    }

    public function assign_stayCation(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'staycations' => 'required'
        ]);

        if($validation->passes())
        {
            $data = [];
            $ctr = 0;
            $timeStamp = now();
            foreach ($request->staycations as $staycation)
            {
                $data[$ctr] = [
                    'user_id' => $request->user,
                    'staycation_id' => $staycation,
                    'created_at' => $timeStamp,
                    'updated_at' => $timeStamp
                ];
                $ctr++;
            }

            if(DB::table('staycation_user')->insert($data))
                return response()->json(['success' => true, 'message' => 'Staycations assigned to user']);
        }
        return response()->json($validation->errors());
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function all_assigned_staycation(UserDataTable $userDataTable, $userId)
    {
        if(auth()->user()->hasRole('agent'))
        {
            $staycations = User::findOrFail($userId)->stayCationLists;
        }else{
            $staycations = Staycation::all();
        }
        return $userDataTable->assigned_stayCations($staycations);
    }

    /**
     * @param $assignedStayCation
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function remove_assigned_staycation($stayCation, $user)
    {
        if(DB::table('staycation_user')->where('staycation_id','=',$stayCation)->where('user_id','=',$user
        )->delete())
            return response()->json(['success' => true, 'message' => 'Staycation Assigned Removed!']);
    }

}
