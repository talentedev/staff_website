@extends('adminlte::page')

@section('title', 'Pheramor - Customers')

@section('content_header')
    <h1>Customers</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customers</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-body table-responsive">
            <table id="customers_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Note</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customers as $key => $customer)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $customer->name }}</td>
                        <td class="text-center">{{ $customer->sales_email }}</td>
                        <td class="text-center">{{ $customer->source }}</td>
                        <td class="text-center">{{ date('Y-m-d', strtotime($customer->created_at)) }}</td>
                        <td class="text-center">{{ $customer->note }}</td>
                        <td class="text-center text-red h4">
                            <i class="fa fa-edit pointer edit-user" data-user="{{ $customer }}"></i>
                            <i class="fa fa-trash pointer delete-user" data-user="{{ $customer }}"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary" id="add_customer">Add Customer</button>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <!-- Modal -->
    <div class="modal fade" id="add_account_modal" tabindex="-1" role="dialog" aria-labelledby="staffModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="customer_form" data-toggle="validator" role="form">
                        <div class="form-group">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" rows='5'></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_data" data-state=''>Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Are you sure to delete the user?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modal-btn-yes">Yes</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/customers.css') }}">
@endpush

@push('js')
    <!-- Common JS -->
    <script src="{{ asset('js/common.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/customers.js') }}"></script>
@endpush