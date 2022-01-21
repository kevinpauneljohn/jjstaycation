<?php

namespace App\Services\Settings;

use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolesDataTable
{
    public function roles()
    {
        return DataTables::of(Role::where('name','!=','super admin')->get())
            ->addColumn('action', function($role){
                $action = '';
                if(auth()->user()->can('edit role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-role-btn" id="'.$role->id.'" data-toggle="modal" data-target="#edit-role-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete role'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-role-btn" id="'.$role->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->make(true);
    }
}
