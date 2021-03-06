@extends('adminlte::page')

@section('title', 'Pheramor - Staff')

@section('content_header')
    <h1>Staff</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Staff</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-body table-responsive">
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Access Code</th>
                        <th class="text-center">Tag</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if($users->count() > 0)
                    @foreach ($users as $key => $user)
                        @if(!$user->hasRole('super admin'))
                        <tr>
                            <td class="text-center">{{ $key }}</td>
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->roles->pluck('name')[0] }}</td>
                            <td class="text-center">{{ $user->source }}</td>
                            <td class="text-center">{{ $user->tag }}</td>
                            <td class="text-center text-red h4">
                                <i class="fa fa-edit pointer edit-user" data-user="{{ $user }}" data-role="{{ $user->roles->pluck('name') }}"></i>
                                <i class="fa fa-trash pointer delete-user" data-user="{{ $user }}"></i>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Record Found</td>
                    </tr>
                @endif
                
                </tbody>
            </table>
            <button class="btn btn-primary" id="add_account">Add Account</button>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <!-- Modal -->
    <div class="modal fade" id="add_account_modal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalLabel">Add Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="user_form" data-toggle="validator" role="form">
                        <div class="form-group row">
                            <label for="name">Account Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group row">
                            <label for="email">Email Address</label>
                            <input type="text" class="form-control" id="email" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group row">
                            <label for="role">Account Type</label>
                            <select class="form-control" id="role" required>
                                @if($roles->count() > 0)
                                    @foreach ($roles as $role)
                                        @if($role->name != "super admin")
                                            <option>{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    No Record Found
                                @endif
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 pl-0">
                                <label for="code">Access Code</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="code">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="button" class="btn btn-primary" id="generate_code" value="Generate"></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tag">Tag</label>
                            <input type="text" class="form-control" id="tag" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="row">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btn_save_data" data-state=''>Save</button>    
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Are you sure to delete the user?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-btn-yes">Yes</button>
                    <button type="button" class="btn btn-default" id="modal-btn-no">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="result_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss='modal' id="btn_result_modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <!-- iCheck -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/plugins/iCheck/all.css') }}">
    <!-- custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/users.css') }}">
@endpush

@push('js')
    <!-- Common JS -->
    <script src="{{ asset('js/common.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/users.js') }}"></script>
@endpush