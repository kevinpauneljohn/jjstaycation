<?php

namespace App\Services\Staycation\Owners;

use App\Models\User;
use Yajra\DataTables\DataTables;

class Owners
{
    public function owners($query)
    {
        return DataTables::of($query)
            ->editColumn('email', function ($owner){
                return '<a href="mailto:'.$owner->email.'">'.$owner->email.'</a>';
            })
            ->addColumn('action', function($owner){
                $action = '';
                if(auth()->user()->can('view owner'))
                {
                    $action .= '<a href="'.route('owners.show',['owner' => $owner->id]).'" class="btn btn-xs btn-success view-owner-btn" id="'.$owner->id.'" title="View"><i class="fa fa-eye"></i></a> ';
                }
                if(auth()->user()->can('edit owner'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-owner-btn" id="'.$owner->id.'" data-toggle="modal" data-target="#edit-owner-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete owner'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-owner-btn" id="'.$owner->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['email','action'])
            ->make(true);
    }

    public function trashedOwners($query)
    {
        return DataTables::of($query)
            ->addColumn('action', function($owner){
                $action = '';
                if(auth()->user()->can('restore owner'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary restore-owner-btn" id="'.$owner->id.'" title="Restore"><i class="fas fa-trash-restore"></i></a> ';
                }
                if(auth()->user()->can('permanent delete owner'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-owner-specific-btn" id="'.$owner->id.'" title="Delete Permanently"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
