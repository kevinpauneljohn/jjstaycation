@extends('adminlte::page')

@section('title', 'Owners')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Owners</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Owners</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add owner')
                    <button class="btn btn-primary btn-sm add-owner-btn" data-toggle="modal" data-target="#owner-modal">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <a href="{{route('display.all.trashed.owners')}}">View Trashed</a>
                </div>
                <table id="owners-list" class="table table-hover table-bordered" role="grid">
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

    @can('add owner')
        <!--add new owner modal-->
        <div class="modal fade" id="owner-modal">
            <form role="form" id="add-owner-form" class="form-submit" autocomplete="off">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Owner</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group firstname">
                                        <label for="firstname">First Name</label><span class="required">*</span>
                                        <input type="text" name="firstname" class="form-control" id="firstname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group middlename">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" name="middlename" class="form-control" id="middlename">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group lastname">
                                        <label for="lastname">Last Name</label><span class="required">*</span>
                                        <input type="text" name="lastname" class="form-control" id="lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group email">
                                        <label for="email">Email</label><span class="required">*</span>
                                        <input type="text" name="email" class="form-control" id="email">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group username">
                                        <label for="username">Username</label><span class="required">*</span>
                                        <input type="text" name="username" class="form-control" id="username">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group password">
                                        <label for="password">Password</label><span class="required">*</span>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group password_confirmation">
                                        <label for="password_confirmation">Confirm Password</label><span class="required">*</span>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-permission-btn" value="Save">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new permission modal-->
    @endcan
@stop
@section('plugins.Datatables', true)
@section('css')

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
                    ajax: '{!! route('all.owners.list') !!}',
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
