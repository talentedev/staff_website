@extends('adminlte::page')

@section('title', 'Pheramor - Settings')

@section('content_header')
    <h1>Settings</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Settings</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-body container-fluid">
            <div class="col-md-6">
                <form id="setting_form" data-toggle="validator" role="form" class="col-md-10">
                    <h3>Acccount Setting</h3>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" value="{{ $user->name }}" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" data-minlength="6">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_pass">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_pass" data-match="#password" data-match-error="Whoops, password don't match">
                        <div class="help-block with-errors"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Save</button>
                </form>
            </div>
            <div class="col-md-6">
                @if(Auth::user()->hasRole('super admin'))
                <form id="agile_form" data-toggle="validator" role="form" class="col-md-10">
                    <h3>AgileCRM Configuration</h3>
                    <div class="form-group">
                        <label for="name">Domain For AgileCRM</label>
                        <input type="text" class="form-control" id="agile_domain" value="{{ $configs[0]->agile_domain }}" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="name">Email For AgileCRM</label>
                        <input type="email" class="form-control" id="agile_email" value="{{ $configs[0]->agile_email }}" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label for="name">API Key For AgileCRM</label>
                        <input type="text" class="form-control" id="api_key" value="{{ $configs[0]->agile_key }}" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn_submit_agile">Save</button>
                </form>
                @endif
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="box">
        <div class="box-header">
            <i class="ion ion-clipboard"></i>
            <h3 class="box-title">Activity Logs</h3>
        </div>
        <div class="box-body">
            @if(count($logs) > 0)
                @foreach($logs as $log)
                    <p>{{ $log->description }}</p>
                @endforeach
            @else
                There are no logs.
            @endif
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@stop

@push('css')
    <!-- custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/settings.css') }}">
@endpush

@push('js')
    <!-- Custom JS -->
    <script src="{{ asset('js/settings.js') }}"></script>
@endpush