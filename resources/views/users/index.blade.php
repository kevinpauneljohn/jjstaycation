@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
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
            <div class="card-body">
                <table id="user-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Date Of Birth</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add user')
        <!--add new roles modal-->
        <div class="modal fade" id="user-modal">
            <form role="form" class="form-submit" autocomplete="off">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Role</h4>
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
                                <div class="col-lg-12">
                                    <div class="form-group address">
                                        <label for="address">Address</label>
                                        <textarea name="address" class="form-control" id="address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group date_of_birth">
                                        <label>Date of Birth:</label>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group roles">
                                        <label>Assign Role</label><span class="required">*</span>
                                        <select name="roles[]" class="select2" id="roles" multiple="multiple" style="width: 100%">
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group username">
                                        <label for="username">Username</label><span class="required">*</span>
                                        <input type="text" name="username" class="form-control" id="username">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group email">
                                        <label for="email">Email</label><span class="required">*</span>
                                        <input type="email" name="email" class="form-control" id="email">
                                    </div>
                                </div>
                            </div>
                            <div class="row password-section">
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
                            <input type="submit" class="btn btn-primary submit-role-btn" value="Save">
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
    @if(auth()->user()->can('add user') || auth()->user()->can('view user'))
        <script>
            $(function() {
                $('#user-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/all-users',
                    columns: [
                        { data: 'fullname', name: 'fullname'},
                        { data: 'username', name: 'username'},
                        { data: 'email', name: 'email'},
                        { data: 'date_of_birth', name: 'date_of_birth', orderable: false},
                        { data: 'roles', name: 'roles'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            $('.select2').select2({
                allowClear: true,
                placeholder: 'Assign a role'
            });

        </script>
    @endif

@stop
