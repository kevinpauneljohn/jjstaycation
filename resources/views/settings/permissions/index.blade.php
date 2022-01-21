@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permissions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Permissions</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add permission')
                    <button class="btn btn-primary btn-sm add-permission-btn" data-toggle="modal" data-target="#add-permission-modal">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="permission-list" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Permissions</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add permission')
        <!--add new roles modal-->
        <div class="modal fade" id="add-permission-modal">
            <form role="form" id="add-permission-form" class="form-submit" autocomplete="off">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group permission">
                                <label for="permission">Permission</label><span class="required">*</span>
                                <input type="text" name="permission" class="form-control" id="permission">
                            </div>
                            <div class="form-group roles">
                                <label>Assign Role</label>
                                <select name="roles[]" class="select2" id="roles" multiple="multiple" style="width: 100%">
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
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
@section('plugins.Select2', true)
@section('css')

@stop

@section('js')
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/settings/permission.js')}}"></script>

    <script>
        $(function() {
            $('#permission-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('all.permissions.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'roles', name: 'roles',orderable: false},
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
@stop
