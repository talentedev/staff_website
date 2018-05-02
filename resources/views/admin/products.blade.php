@extends('adminlte::page')

@section('title', 'Pheramor - Products')

@section('content_header')
    <h1>Products</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="products_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><input type="checkbox" class="minimal"></th>
                        <th class="text-center">Pheramor ID</th>
                        <th class="text-center">Sales Email</th>
                        <th class="text-center">Account Email</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Sales</th>
                        <th class="text-center">Account Connected</th>
                        <th class="text-center">Swab Returned</th>
                        <th class="text-center">Ship To Lab</th>
                        <th class="text-center">Lab Received</th>
                        <th class="text-center">Sequeced</th>
                        <th class="text-center">Uploaded</th>
                        <th class="text-center">Bone Marrow Consent</th>
                        <th class="text-center">Bone Marrow Shared</th>
                        <th class="text-center">Note</th>
                        <th class="text-center th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td class="text-center"><input type="checkbox" class="minimal"></td>
                        <td class="text-center">{{ $product->pheramor_id }}</td>
                        <td class="text-center">{{ $product->sales_email }}</td>
                        <td class="text-center">{{ $product->account_email }}</td>
                        <td class="text-center">{{ $product->source }}</td>
                        <td class="text-center">{{ date('Y-m-d', strtotime($product->created_at)) }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->sales_date }}</td>
                        <td class="text-center">{{ $product->note }}</td>
                        <td class="text-center text-danger">
                            <button class="btn btn-xs btn-success">update</button>
                            <button class="btn btn-xs btn-info">note</button>
                            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
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
                            <label for="pheramor_id">Pheramor ID</label>
                            <input type="text" class="form-control" id="pheramor_id" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label for="sales_email">Sales Email</label>
                            <input type="email" class="form-control" id="sales_email" required>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/products.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/products.js') }}"></script>
@endpush