@extends('adminlte::page')

@section('title', 'Trashed Owners')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Trashed Owners</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('owners.index')}}">Owners</a></li>
                <li class="breadcrumb-item active">Trashed Owners</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('restore owner')
                    <button class="btn btn-primary btn-xs restore-all-owner-btn" @if($userTrashed === 0) disabled="disabled" @endcan>Restore All</button>
                @endcan
                @can('delete owner')
                    <button class="btn btn-danger btn-xs delete-permanent-owner-btn" @if($userTrashed === 0) disabled="disabled" @endcan>Permanently Delete All</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="owners-list" class="table table-hover table-bordered" role="grid" style="width: 100% !important;">
                    <thead>
                    <tr role="row">
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


@stop
@section('plugins.Datatables', true)
@section('css')
    <style>
        .card-header button{
            margin-right: 2px;
        }
    </style>
@stop

@section('js')
    @can('view owner')
        <script src="{{asset('js/errorChecker.js')}}"></script>
        <script src="{{asset('js/errorDisplay.js')}}"></script>
        <script src="{{asset('/js/StayCation/owners/owners.js')}}"></script>
        <script>
            $(function() {
                $('#owners-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('all.trashed.owners') !!}',
                    columns: [
                        { data: 'firstname', name: 'firstname'},
                        { data: 'middlename', name: 'middlename'},
                        { data: 'lastname', name: 'lastname'},
                        { data: 'email', name: 'email'},
                        { data: 'username', name: 'username'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
