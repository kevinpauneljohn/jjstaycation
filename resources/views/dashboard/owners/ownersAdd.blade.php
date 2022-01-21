@extends('adminlte::page')

@section('title', 'Add Owner')

@section('content_header')
    <h1>Add Owner</h1>
@stop

@section('content')
    <div id="app">
        <div class="container">
            <add-owners-component></add-owners-component>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{mix('/js/app.js')}}"></script>
@stop
