@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Roles</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">roles</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add role')
                    <button class="btn btn-primary btn-sm add-role-btn" data-toggle="modal" data-target="#add-role-modal">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="roles-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add role')
        <!--add new roles modal-->
        <div class="modal fade" id="add-role-modal">
            <form role="form" id="add-role-form" class="form-submit" autocomplete="off">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Role</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group role">
                                <label for="role">Role</label><span class="required">*</span>
                                <input type="text" name="role" class="form-control" id="role">
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
@section('plugins.Datatables', true)
@section('css')

@stop

@section('js')
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/settings/roles.js')}}"></script>

    <script>
        $(function() {
            $('#roles-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all.roles.list') !!}',
                columns: [
                    { data: 'name', name: 'name',orderable: false},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });
    </script>
@stop
