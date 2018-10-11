@extends('adminlte::page')

@section('title', 'Pheramor - Email Settings')

@section('content_header')
    <h1>Email Settings</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Email Settings</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6" id="login_log">
            <div class="box">
                <div class="box-header">
                    <i class="fa fa-envelope"></i>
                    <h3 class="box-title">Status Update Email</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <input type="checkbox" class="minimal" id="ship_update_email"> <label> When Ship Date is Updated</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="minimal" id="sales_update_email"> <label> When Sales Date is Updated</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="minimal" id="account_update_email"> <label> When Account Connected Date is Updated</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="minimal" id="swab_update_email"> <label> When Swab Returned Date is Updated</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="minimal" id="sequence_update_email"> <label> When Sequeced Date is Updated</label>
                    </div>
                    <button type="button" class="btn btn-primary" id="save_update_email">Save</button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6" id="agile_log">
            <div class="box">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Reminder Email</h3>
                </div>
                <div class="box-body">
                   <div class="form-group">
                        <label>First reminder &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: after </label>
                        <select id="first_reminder_email">
                            <option>1</option>
                            <option>3</option>
                            <option>5</option>
                            <option>7</option>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                            <option>25</option>
                            <option>30</option>
                        </select>
                        <label> Days</label>
                    </div>
                    <div class="form-group">
                        <label>Second reminder : after </label>
                        <select id="second_reminder_email">
                            <option>1</option>
                            <option>3</option>
                            <option>5</option>
                            <option>7</option>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                            <option>25</option>
                            <option>30</option>
                        </select>
                        <label> Days</label>
                    </div>
                    <button type="button" class="btn btn-primary" id="save_reminder_email">Save</button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <input type="hidden" name="" id="setting_values" data-settings="{{ $settings }}">
@stop

@push('css')
    <!-- iCheck -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/plugins/iCheck/all.css') }}">
@endpush

@push('js')
    <!-- Custom JS -->
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/emails.js') }}"></script>
@endpush