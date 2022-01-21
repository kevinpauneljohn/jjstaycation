<?php

namespace App\Services\Settings;

use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionsDataTable
{
    public function permissions()
    {
        return DataTables::of(Permission::all())
            ->addColumn('roles', function($permission){
                $roles = '';
                foreach (collect($permission->roles)->pluck('name') as $role){
                    $roles .= '<span class="badge badge-info right role-badge">'.$role.'</span> ';
                }
                return $roles;
            })
            ->addColumn('action', function($permission){
                $action = '';
                if(auth()->user()->can('edit permission'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-permission-btn" id="'.$permission->id.'" data-toggle="modal" data-target="#edit-permission-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete permission'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-permission-btn" id="'.$permission->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action','roles'])
            ->make(true);
    }
}
