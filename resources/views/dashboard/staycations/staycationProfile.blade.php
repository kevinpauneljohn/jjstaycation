@extends('adminlte::page')

@section('title', 'Staycation Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ucwords($staycation->name)}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('owners.show',['owner' => $staycation->owner->id])}}">{{$staycation->owner->fullname}}</a></li>
                <li class="breadcrumb-item active">Staycation Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">



                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-map-marked-alt mr-1"></i> Address</strong>

                            <p class="text-muted">{{$staycation->full_address}}</p>
                            <hr>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" href="#appointments" data-toggle="tab">Appointments</a></li>
                           @can('view staycation package') <li class="nav-item"><a class="nav-link" href="#packages" data-toggle="tab">Packages</a></li>@endcan
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="appointments">
                                    @can('add appointment')
                                        <button class="btn btn-primary btn-sm add-staycation-btn mb-3" data-toggle="modal" data-target="#staycation-modal">Add</button>
                                    @endcan
{{--                                    <table id="staycations-list" class="table table-hover table-bordered" role="grid">--}}
{{--                                        <thead>--}}
{{--                                        <tr role="row">--}}
{{--                                            <th style="width: 30%;">Name</th>--}}
{{--                                            <th style="width: 30%;">Address</th>--}}
{{--                                            <th style="width: 20%">Created by</th>--}}
{{--                                            <th style="width: 15%;">Action</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                    </table>--}}
                                </div>
                                <!-- /.tab-pane -->
                                @can('view staycation package')
                                <div class="tab-pane" id="packages">
                                    <!-- Packages -->
                                    @can('add staycation package')
                                        <button class="btn btn-primary btn-sm add-staycation-package-btn mb-3">Add</button>
                                    @endcan
                                    <table id="packages-list" class="table table-hover table-bordered" role="grid" style="width: 100%;">
                                        <thead>
                                        <tr role="row">
                                            <th>Name</th>
                                            <th>Pax</th>
                                            <th>Amount</th>
                                            <th>Color</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                @endcan

                                <div class="tab-pane" id="settings">
                                    settings
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    @if(auth()->user()->can('add staycation package') || auth()->user()->can('edit staycation package'))
        <!--add new package modal-->
        <div class="modal fade" id="package-modal">
            <form role="form" id="add-package-form" class="form-submit" autocomplete="off">
                @csrf
                <input type="hidden" name="staycation" value="{{$staycation->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Package</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <x-package-form></x-package-form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-package-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new staycation modal-->
    @endif
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.ColorPicker', true)
@section('css')
@stop

@section('js')
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/number-formatter.js')}}"></script>
    <script>
        $(function() {
            $('#packages-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('owner.package.list',['stayCationId' => $staycation->id]) !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'pax', name: 'pax'},
                    { data: 'amount', name: 'amount'},
                    { data: 'color', name: 'color'},
                    { data: 'created_by', name: 'created_by'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
    </script>
    @can('add staycation package')
        <script src="{{asset('/js/StayCation/package.js')}}"></script>
    @endcan
    @can('view staycation package')
        <script>


            numberFormatter('#amount, #pax');
        </script>
    @endcan
@stop
