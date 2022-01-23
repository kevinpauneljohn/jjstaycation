@extends('adminlte::page')

@section('title', 'Owner Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Owner Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('owners.index')}}">Owners</a></li>
                <li class="breadcrumb-item active">Owner Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="https://picsum.photos/300/300" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{$user->fullname}}</h3>

                            <p class="text-muted text-center">{{collect($user->getRoleNames())->first()}}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-mail-bulk mr-1"></i> Email</strong>

                            <p class="text-muted">
                                <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                            </p>
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
                            <li class="nav-item"><a class="nav-link active" href="#staycations" data-toggle="tab">Resorts</a></li>
                            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="staycations">
                                    @can('add staycation')
                                        <button class="btn btn-primary btn-sm add-staycation-btn mb-3" data-toggle="modal" data-target="#staycation-modal">Add</button>
                                    @endcan
                                    <table id="staycations-list" class="table table-hover table-bordered" role="grid">
                                        <thead>
                                        <tr role="row">
                                            <th style="width: 30%;">Name</th>
                                            <th style="width: 30%;">Address</th>
                                            <th style="width: 20%">Created by</th>
                                            <th style="width: 15%;">Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="timeline">
                                    <!-- The timeline -->
                                    timeline
                                </div>
                                <!-- /.tab-pane -->

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

    @can('add staycation')
        <!--add new staycation modal-->
        <div class="modal fade" id="staycation-modal">
            <form role="form" id="add-staycation-form" class="form-submit" autocomplete="off">
                @csrf
                <input type="hidden" name="owner" value="{{$user->id}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Resort</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group name">
                                        <label for="name">Name</label><span class="required">*</span>
                                        <input type="text" name="name" class="form-control" id="name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group address_number">
                                        <label for="address_number">Block/Lot/Bldg. No./Street Name</label><span class="required">*</span>
                                        <input type="text" name="address_number" class="form-control" id="address_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group region">
                                        <label for="region">Region</label><span class="required">*</span>
                                        <select class=" form-control" name="region" id="region" style="width: 100%">
                                            <option></option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->region_code}}">{{$region->region_description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group province">
                                        <label for="province">Province</label><span class="required">*</span>
                                        <select class="select2-province form-control" name="province" id="province" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group city">
                                        <label for="city">City</label><span class="required">*</span>
                                        <select class="select2-city form-control" name="city" id="city" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group barangay">
                                        <label for="barangay">Barangay</label>
                                        <select class="select2-barangay form-control" name="barangay" id="barangay" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group description">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-staycation-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new staycation modal-->
    @endcan

@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('css')

@stop

@section('js')
    @can('view staycation')
        <script src="{{asset('js/errorChecker.js')}}"></script>
        <script src="{{asset('js/errorDisplay.js')}}"></script>
        <script src="{{asset('/js/StayCation/address.js')}}"></script>
        <script src="{{asset('/js/StayCation/staycation.js')}}"></script>
        <script>
            $(function() {
                $('#staycations-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('owner.staycation.list',['owner' => $user->id]) !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'address', name: 'address'},
                        { data: 'created_by', name: 'created_by'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });

            $('.select2-region').select2({
                allowClear: true,
                placeholder: 'Select Region'
            });
            $('.select2-province').select2({
                allowClear: true,
                placeholder: 'Select Province'
            });
            $('.select2-city').select2({
                allowClear: true,
                placeholder: 'Select City'
            });
            $('.select2-barangay').select2({
                allowClear: true,
                placeholder: 'Select Barangay'
            });
        </script>
    @endcan
@stop
