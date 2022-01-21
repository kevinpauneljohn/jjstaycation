<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;

class UserDataTable
{
    public function users($query)
    {
        return DataTables::of($query)
            ->addColumn('roles', function($user){
                $roles = '';
                foreach (collect($user->roles)->pluck('name') as $role){
                    $roles .= '<span class="badge badge-info right role-badge">'.$role.'</span> ';
                }
                return $roles;
            })
            ->editColumn('email',function($user){
                return '<a href="mailto:'.$user->email.'">'.$user->email.'</a>';
            })
            ->editColumn('date_of_birth',function($user){
                return isset($user->date_of_birth) ? $user->date_of_birth->format('M d, Y') : '';
            })
            ->addColumn('fullname',function($user){
                return $user->fullname;
            })
            ->addColumn('action', function($user){
            $action = '';
            if(auth()->user()->can('view user'))
            {
                $action .= '<a href="'.route('users.show',['user' => $user->id]).'" class="btn btn-xs btn-success view-user-btn" id="'.$user->id.'" title="View"><i class="fa fa-eye"></i></a> ';
            }
            if(auth()->user()->can('edit user'))
            {
                $action .= '<a class="btn btn-xs btn-primary edit-user-btn" id="'.$user->id.'" title="Edit"><i class="fa fa-edit"></i></a> ';
            }
            if(auth()->user()->can('delete user'))
            {
                $action .= '<a class="btn btn-xs btn-danger delete-user-btn" id="'.$user->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
            }
            return $action;
        })
            ->rawColumns(['roles','email','action'])
            ->make(true);
    }

    public function assigned_stayCations($query)
    {
        return DataTables::of($query)
            ->editColumn('name',function($stayCation){
                return ucwords($stayCation->name);
            })
            ->addColumn('full_address',function($stayCation){
                return $stayCation->full_address;
            })
            ->addColumn('action', function($stayCation){
                $action = '';
                if(auth()->user()->can('view staycation'))
                {
                    $action .= '<a href="'.route('staycations.show',['staycation' => $stayCation->id]).'" class="btn btn-xs btn-success view-assigned-staycation-btn" id="'.$stayCation->id.'" title="View"><i class="fa fa-eye"></i></a> ';
                }
                if(auth()->user()->can('add booking'))
                {
                    $action .= '<a href="'.route('assigned-staycations.show',['assigned_staycation' => $stayCation->id]).'" class="btn btn-xs btn-primary view-assigned-staycation-btn" id="'.$stayCation->id.'" title="View"><i class="fa fa-calendar-alt"></i></a> ';
                }
                if(auth()->user()->can('remove assigned staycation'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-assigned-staycation-btn" id="'.$stayCation->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
