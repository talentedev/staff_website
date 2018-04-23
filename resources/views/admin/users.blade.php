@extends('adminlte::page')

@section('title', 'Pheramor - Accounts')

@section('content_header')
    <h1>Accounts</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Accounts</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Access Code</th>
                        <th class="text-center">API Key</th>
                        <th class="text-center">Tag</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">{{ $user->source }}</td>
                        <td class="text-center">{{ $user->api_key }}</td>
                        <td class="text-center">{{ $user->tag }}</td>
                        <td class="text-center text-red h4">
                            <i class="fa fa-edit pointer edit-user" data-user="{{ $user }}"></i>
                            <i class="fa fa-trash pointer delete-task" data-id="{{ $user->id }}"></i>
                        </td>
                    </tr>
                @endforeach
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
                    <h5 class="modal-title" id="staffModalLabel">Add Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group row">
                            <label for="exampleInputEmail1">Staff Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 pl-0">
                                <label for="exampleInputEmail1">Access Code</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="code">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="button" class="btn btn-primary" id="generate_code" value="Generate"></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputEmail1">Email Address</label>
                            <input type="text" class="form-control" id="email">
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputEmail1">API Key</label>
                            <input type="text" class="form-control" id="key">
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputEmail1">Tag</label>
                            <input type="text" class="form-control" id="tag">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_save_data" data-state=''>Save</button>
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