@extends('adminlte::page')

@section('title', 'Bookings')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bookings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Bookings</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add user')
                    <button class="btn btn-primary btn-sm add-user-btn" data-toggle="modal">Add</button>
                @endcan
            </div>
            <div class="card-body table-responsive">
               <x-booking.bookings />
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

@stop
