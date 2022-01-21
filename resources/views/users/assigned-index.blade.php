@extends('adminlte::page')

@section('title', 'Assigned Resorts')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Assigned Resorts</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('staycations.index')}}">Resorts</a></li>
                <li class="breadcrumb-item active">Assigned Resorts</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                header
            </div>
            <div class="card-body">
                <table id="assigned-staycation-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Name</th>
                        <th>Address</th>
                        <th style="width: 15%;">Action</th>
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
    @can('view assigned staycations')
        <script>
            $(function() {
                $('#assigned-staycation-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('get.assigned.user.staycations',['user' => auth()->user()->id])}}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'full_address', name: 'full_address'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
        </script>
    @endcan
@stop
