@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Customers</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Customers</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add customer')
                    <button class="btn btn-primary btn-sm add-user-btn" data-toggle="modal">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="users-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Facebook</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@stop
@section('plugins.Moment', true)
@section('plugins.Datatables', true)
@section('plugins.DateRangePicker', true)
@section('plugins.TempusDominus', true)
@section('plugins.Select2', true)
@section('css')

@stop

@section('js')
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/users.js')}}"></script>
        <script>
            $(function() {
                $('#users-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('all.customers.list') !!}',
                    columns: [
                        { data: 'date', name: 'date'},
                        { data: 'fullname', name: 'fullname'},
                        { data: 'email', name: 'email'},
                        { data: 'mobile_number', name: 'mobile_number', orderable: false},
                        { data: 'facebook_url', name: 'facebook_url', orderable: false},
                        { data: 'created_by', name: 'created_by'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>

@stop
