@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">User Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                <li class="breadcrumb-item active">Users Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9">
                <div class="card card-default">
                    <div class="card-header">
                        header
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle" src="https://picsum.photos/300/300" alt="User profile picture">
                                            </div>

                                            <h3 class="profile-username text-center">{{$user->fullname}}</h3>

                                            <p class="text-muted text-center">
                                                @if(collect($user->getRoleNames())->count() > 0)
                                                    @foreach($user->getRoleNames() as $role)
                                                        <span class="badge badge-info right role-badge">{{$role}}</span>
                                                    @endforeach
                                                @endif
                                            </p>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                    <!-- About Me Box -->
                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h3 class="card-title">Account Information</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <strong><i class="fas fa-user mr-1"></i> Username</strong>

                                            <p class="text-muted">
                                                {{$user->username}}
                                            </p>
                                            <hr>
                                            <strong><i class="fas fa-mail-bulk mr-1"></i> Email</strong>

                                            <p class="text-muted">
                                                <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                                            </p>
                                            <hr>

                                            <strong><i class="fas fa-birthday-cake mr-1"></i> Date of Birth</strong>

                                            <p class="text-muted">
                                                {{$user->date_of_birth->format('M d, Y')}}
                                            </p>
                                            <hr>
                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                                            <p class="text-muted">
                                                {{ucfirst($user->address)}}
                                            </p>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                            </div>

                            <div class="col-lg-8">
                                <div class="card card-default">
                                    <div class="card-header">
                                        @can('assign staycation')
                                            <button type="button" class="btn btn-primary btn-xs mb-2 assign-user-btn">Assign</button>
                                        @endcan
                                    </div>
                                    <div class="card-body">
                                        <h4>Assigned StayCations</h4>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-default">
                    <div class="card-header">
                        header here
                    </div>
                    <div class="card-body">
                        body here
                    </div>
                </div>
            </div>
        </div>
    </div>


    @can('assign staycation')
        <!--add new roles modal-->
        <div class="modal fade" id="assign-staycation-modal">
            <form role="form" class="form-submit" autocomplete="off">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Assign Staycation</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="user" value="{{$user->id}}">
                            <div class="form-group staycations">
                                <label>Assign Staycations</label><span class="required">*</span>
                                <select name="staycations[]" class="select2" id="staycations" multiple="multiple" style="width: 100%">
                                    @foreach($staycations as $staycation)
                                        <option value="{{$staycation->id}}" @if(collect($assignedStayCations)->contains($staycation->id)) disabled @endif>{{$staycation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary submit-assign-btn" value="Save">
                        </div>
                        <div class="staycation-details p-3"></div>
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
    <script src="{{asset('js/users/user-profile.js')}}"></script>
    @if(auth()->user()->can('view user'))
        <script>
            $(function() {
                $('#assigned-staycation-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{route('get.assigned.user.staycations',['user' => $user->id])}}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'full_address', name: 'full_address'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            $('.select2').select2({
                allowClear: true,
                placeholder: 'Select a staycation'
            });

        </script>
    @endif

@stop
