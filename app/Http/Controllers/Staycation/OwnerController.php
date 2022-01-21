<?php

namespace App\Http\Controllers\Staycation;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditOwnersRequest;
use App\Http\Requests\OwnersRequest;
use App\Models\User;
use App\Services\Staycation\Owners\Owners;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view owner')->only(['index','allOwners']);
        $this->middleware('permission:edit owner')->only(['edit','update']);
        $this->middleware('permission:restore owner')->only(['restoreAllTrashed','restoreOwner']);
        $this->middleware('permission:delete owner')->only(['destroy']);
        $this->middleware('permission:permanent delete owner')->only(['allTrashedOwners','deletePermanentOwner','permanentlyDeleteSpecificUser']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('dashboard.owners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.owners.ownersAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OwnersRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OwnersRequest $request)
    {
        $request->validated();
        User::create(array_merge($request->all(),['created_by' => auth()->user()->id]))->assignRole('owner');
        return response()->json(['success' => true, 'message' => 'Owner successfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('dashboard.owners.ownersProfile',[
            'user' => User::role('owner')->where('id',$id)->first(),
            'regions' => DB::table('philippine_regions')->get()
        ]);
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
    public function update(EditOwnersRequest $request, $id)
    {
        $request->validated();
        $owner = User::findOrFail($id);
        $owner->firstname = $request->firstname;
        $owner->middlename = $request->middlename;
        $owner->lastname = $request->lastname;
        $owner->email = $request->email;
        if($owner->isDirty())
        {
            $owner->save();
            return response()->json(['success' => true, 'message' => 'Owner was updated']);
        }
        return response()->json(['success' => false, 'message' => 'No changes occurred']);
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
            return response()->json(['success' => true, 'message' => 'Owner successfully removed']);
    }

    /**
     * permanently delete all owners from trash
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function deletePermanentOwner()
    {
        $owners = User::role('owner')->onlyTrashed();
        $this->removeRoleBeforePermanentlyDeleted($owners->get());
            return response()->json(['success' => true, 'message' => 'Owners permanently removed']);
    }

    /**
     * @param $users
     * @return void
     */
    private function removeRoleBeforePermanentlyDeleted($users)
    {
        foreach ($users as $user){
            $user->syncRoles([]);
            $user->forceDelete();
        }
    }

    public function permanentlyDeleteSpecificUser($id)
    {
        $owner = User::onlyTrashed()->where('id',$id)->first();
        $owner->syncRoles([]);
        if($owner->forceDelete())
            return response()->json(['success' => true, 'message' => 'Owners permanently removed']);
    }

    /**
     * @param Owners $owners
     * @return mixed
     */
    public function allOwners(Owners $owners)
    {
        return $owners->owners(User::role('owner')->get());
    }

    /**
     * @param Owners $owners
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function allTrashedOwners(Owners $owners)
    {
        return $owners->trashedOwners(User::role('owner')->onlyTrashed()->get());
    }

    /**
     * display all trash from to the view using datatable
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function displayAllTrashed()
    {
        return view('dashboard.owners.trashedOwners',[
            'userTrashed' => collect(User::role('owner')->onlyTrashed()->get())->count()
        ]);
    }

    /**
     * restore all owners from trashed
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreAllTrashed()
    {
        if(User::onlyTrashed()->restore())
            return response()->json(['success' => true, 'message' => 'All Owners successfully restored']);
    }

    /**
     * restore a specific owner from trash
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function restoreOwner($id)
    {
        if(User::onlyTrashed()->where('id',$id)->restore())
            return response()->json(['success' => true, 'message' => 'Owner successfully restored']);
    }
}
