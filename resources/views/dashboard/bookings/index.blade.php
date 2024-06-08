@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Assigned StayCations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('staycations.index')}}">Staycations</a></li>
                <li class="breadcrumb-item active">Assigned Staycations</li>
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
{{--                {!! $calendar->calendar() !!}--}}
            </div>
        </div>
    </div>
@stop
@section('plugins.Moment', true)
@section('plugins.Datatables', true)
@section('plugins.DateRangePicker', true)
@section('plugins.TempusDominus', true)
@section('plugins.Select2', true)
@section('plugins.FullCalendar', true)
@section('css')

@stop

@section('js')
{{--    {!! $calendar->script() !!}--}}
@stop
