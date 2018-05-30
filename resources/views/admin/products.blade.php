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
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="products_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><input type="checkbox" class="minimal"></th>
                        <th class="text-center">Pheramor ID</th>
                        <th class="text-center">Sales Email</th>
                        <th class="text-center">Account Email</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Sales</th>
                        <th class="text-center">Ship</th>
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
                    <tr class="filters">
                        <th></th>
                        <th class="filter-input">Pheramor ID</th>
                        <th class="filter-input">Sales Email</th>
                        <th class="filter-input">Account Email</th>
                        <th class="filter-input">Phone</th>
                        <th class="filter-input">Source</th>
                        <th class="filter-date">Created Date</th>
                        <th class="filter-date">Sales Date</th>
                        <th class="filter-date">Ship Date</th>
                        <th class="filter-date">Account Connected Date</th>
                        <th class="filter-date">Swab Returned Date</th>
                        <th class="filter-date">Ship To Lab Date</th>
                        <th class="filter-date">Lab Received Date</th>
                        <th class="filter-date">Sequenced Date</th>
                        <th class="filter-date">Uploaded To Server Date</th>
                        <th class="filter-date">Bone Marrow Consent Date</th>
                        <th class="filter-date">Bone Marrow Shared Date</th>
                        <th></th>
                        <th class="text-center">
                            <button class="btn btn-xs btn-danger" id="btn_clear_filter">Clear Filters</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td class="text-center"><input type="checkbox" class="minimal" data-id="{{ $product->id }}"></td>
                        <td class="text-center">{{ $product->pheramor_id }}</td>
                        <td class="text-center">{{ $product->sales_email }}</td>
                        <td class="text-center">{{ $product->account_email }}</td>
                        <td class="text-center">{{ $product->phone }}</td>
                        <td class="text-center">{{ $product->source }}</td>
                        <td class="text-center">{{ $product->created_at === null ? 'n/a' : $product->created_at->format('d-M-Y') }}</td>
                        <td class="text-center">{{ $product->sales_date === null ? 'n/a' : date('d-M-Y', strtotime($product->sales_date)) }}</td>
                        <td class="text-center">{{ $product->ship_date === null ? 'n/a' : date('d-M-Y', strtotime($product->ship_date)) }}</td>
                        <td class="text-center">{{ $product->account_connected_date === null ? 'n/a' : date('d-M-Y', strtotime($product->account_connected_date)) }}</td>
                        <td class="text-center">{{ $product->swab_returned_date === null ? 'n/a' : date('d-M-Y', strtotime($product->swab_returned_date)) }}</td>
                        <td class="text-center">{{ $product->ship_to_lab_date === null ? 'n/a' : date('d-M-Y', strtotime($product->ship_to_lab_date)) }}</td>
                        <td class="text-center">{{ $product->lab_received_date === null ? 'n/a' : date('d-M-Y', strtotime($product->lab_received_date)) }}</td>
                        <td class="text-center">{{ $product->sequenced_date === null ? 'n/a' : date('d-M-Y', strtotime($product->sequenced_date)) }}</td>
                        <td class="text-center">{{ $product->uploaded_to_server_date === null ? 'n/a' : date('d-M-Y', strtotime($product->uploaded_to_server_date)) }}</td>
                        <td class="text-center">{{ $product->bone_marrow_consent_date === null ? 'n/a' : date('d-M-Y', strtotime($product->bone_marrow_consent_date)) }}</td>
                        <td class="text-center">{{ $product->bone_marrow_shared_date === null ? 'n/a' : date('d-M-Y', strtotime($product->bone_marrow_shared_date)) }}</td>
                        <td class="text-center">{{ $product->note }}</td>
                        <td class="text-center text-danger">
                            <button class="btn btn-xs btn-success update-product" data-product="{{ $product }}">update</button>
                            <button class="btn btn-xs btn-info update-note" data-product="{{ $product }}">note</button>
                            <button class="btn btn-xs btn-danger delete-product" data-product="{{ $product }}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <!-- <tfoot>
                    <th></th>
                    <th class="filter-input">Pheramor ID</th>
                    <th class="filter-input">Sales Email</th>
                    <th class="filter-input">Account Email</th>
                    <th>
                        <select class="form-control">
                            <option value="">All</option>
                            <option value="admin">admin</option>
                            <option value="Street Team">Street Team</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </th>
                    <th class="filter-date">Created Date</th>
                    <th class="filter-date">Sales Date</th>
                    <th class="filter-date">Account Connected Date</th>
                    <th class="filter-date">Swab Returned Date</th>
                    <th class="filter-date">Ship To Lab Date</th>
                    <th class="filter-date">Lab Received Date</th>
                    <th class="filter-date">Sequenced Date</th>
                    <th class="filter-date">Uploaded To Server Date</th>
                    <th class="filter-date">Bone Marrow Consent Date</th>
                    <th class="filter-date">Bone Marrow Shared Date</th>
                    <th></th>
                    <th></th>
                </tfoot> -->
            </table>
            <button class="btn btn-primary" id="add_customer">Add Customer</button>
            <button class="btn btn-success" id="update_status_bulk">Update Status</button>
            <input type="checkbox" class="minimal" id="show_advanced_filter">
            <span>&nbsp;Advanced Filters</span>
            <button class="btn bg-teal" id="get_csv_file">Upload CSV</button>
            <input type="file" id="upload_csv" style="display: none;">
            <input type="checkbox" class="minimal" id="csv_ignore_first_row">
            <span>&nbsp;Ignore first row</span>
            <input type="hidden" data-products="{{ $products }}" id="data_products">
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <!-- Add Product Modal -->
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
                            <label for="create_sales_email">Sales Email</label>
                            <input type="email" class="form-control" id="create_sales_email">
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" rows='5'></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_data">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Product Modal -->
    <div class="modal fade" id="update_product_modal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="updateModalLabel">Update Status Dates</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="product_update_form" class="form-horizontal">
                        <div class="form-group text-center">
                            <label id="update_modal_label"></label>
                        </div>
                        <div class="form-group">
                            <label for="sales_date" class="col-sm-4 control-label">Sales Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="sales_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sales_date" class="col-sm-4 control-label">Ship Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="ship_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_connected_date" class="col-sm-4 control-label">Account Connected Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="account_connected_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="swab_returned_date" class="col-sm-4 control-label">Swab Returned Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="swab_returned_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ship_to_lab_date" class="col-sm-4 control-label">Ship To Lab Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="ship_to_lab_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lab_received_date" class="col-sm-4 control-label">Lab Received Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="lab_received_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sequenced_date" class="col-sm-4 control-label">Sequenced Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="sequenced_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uploaded_to_server_date" class="col-sm-4 control-label">Uploaded To Server Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="uploaded_to_server_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bone_marrow_consent_date" class="col-sm-4 control-label">Bone Marrow Consent Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="bone_marrow_consent_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bone_marrow_shared_date" class="col-sm-4 control-label">Bone Marrow Shared Date</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control datepicker" id="bone_marrow_shared_date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sales_email" class="col-sm-4 control-label">Sales Email</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-envelope fa fa-envelope"></i>
                                </span>
                                <input type="text" class="form-control" id="sales_email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_email" class="col-sm-4 control-label">Account Email</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-envelope fa fa-envelope"></i>
                                </span>
                                <input type="text" class="form-control" id="account_email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-4 control-label">Phone</label>
                            <div class="input-prepend input-group col-sm-8">
                                <span class="add-on input-group-addon">
                                    <i class="glyphicon glyphicon-phone fa fa-phone"></i>
                                </span>
                                <input type="number" class="form-control" id="phone" />
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btn_update_status">Save</button>
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
                    <h4 class="modal-title" id="myModalLabel">Change Summary</h4>
                </div>
                <div class="modal-body container-fluid">
                    <div>
                        <span>Sales Date: </span>    
                        <span class="pull-right" id="summary_sales_date">---</span>
                    </div>
                    <div>
                        <span>Ship Date: </span>    
                        <span class="pull-right" id="summary_ship_date">---</span>
                    </div>
                    <div>
                        <span>Account Connected Date: </span>    
                        <span class="pull-right" id="summary_account_connected_date">---</span>
                    </div>
                    <div>
                        <span>Swab Returned Date: </span>    
                        <span class="pull-right" id="summary_swab_returned_date">---</span>
                    </div>
                    <div>
                        <span>Ship To Lab Date: </span>    
                        <span class="pull-right" id="summary_ship_to_lab_date">---</span>
                    </div>
                    <div>
                        <span>Lab Received Date: </span>    
                        <span class="pull-right" id="summary_lab_received_date">---</span>
                    </div>
                    <div>
                        <span>Sequenced Date: </span>    
                        <span class="pull-right" id="summary_sequenced_date">---</span>
                    </div>
                    <div>
                        <span>Uploaded To Server Date: </span>    
                        <span class="pull-right" id="summary_uploaded_to_server_date">---</span>
                    </div>
                    <div>
                        <span>Bone Marrow Consent Date: </span>    
                        <span class="pull-right" id="summary_bone_marrow_consent_date">---</span>
                    </div>
                    <div>
                        <span>Bone Marrow Shared Date: </span>    
                        <span class="pull-right" id="summary_bone_marrow_shared_date">---</span>
                    </div>
                    <div>
                        <span>Sales Email: </span>    
                        <span class="pull-right" id="summary_sales_email"></span>
                    </div>
                    <div>
                        <span>Account Email: </span>    
                        <span class="pull-right" id="summary_account_email"></span>
                    </div>
                    <div>
                        <span>Phone: </span>    
                        <span class="pull-right" id="summary_phone"></span>
                    </div>
                    <br>
                    <label>Are you sure to change them?</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-btn-yes">Yes</button>
                    <button type="button" class="btn btn-default" id="modal-btn-no">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Note Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="note_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update Note</h4>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="5"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_update_note">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Create Callback Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="product_create_callback_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_callback_confirm" data-dismiss=''>OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CSV Confirm Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="csv-mi-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change Summary By CSV File</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-5 table-responsive">
                        <h4>Create Customers</h4>
                        <table class="table table-bordered table-striped" id="csv_create_confirm_table">
                            <thead>
                                <tr>
                                    <th class="text-center">Pheramor ID</th>
                                    <th class="text-center">Sales Email</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <h4>Update Customers</h4>
                        <table class="table table-bordered table-striped" id="csv_update_confirm_table">
                            <thead>
                                <tr>
                                    <th class="text-center">Pheramor ID</th>
                                    <th class="text-center">Sales Email</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7">
                        <form id="product_update_form" class="form-horizontal">
                            <div class="form-group text-center">
                                <label id="update_modal_label"></label>
                            </div>
                            <div class="form-group">
                                <label for="csv_sales_date" class="col-sm-5 control-label">Sales Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_sales_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_ship_date" class="col-sm-5 control-label">Ship Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_ship_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_account_connected_date" class="col-sm-5 control-label">Account Connected Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_account_connected_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_swab_returned_date" class="col-sm-5 control-label">Swab Returned Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_swab_returned_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_ship_to_lab_date" class="col-sm-5 control-label">Ship To Lab Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_ship_to_lab_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_lab_received_date" class="col-sm-5 control-label">Lab Received Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_lab_received_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_sequenced_date" class="col-sm-5 control-label">Sequenced Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_sequenced_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_uploaded_to_server_date" class="col-sm-5 control-label">Uploaded To Server Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_uploaded_to_server_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_bone_marrow_consent_date" class="col-sm-5 control-label">Bone Marrow Consent Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_bone_marrow_consent_date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_bone_marrow_shared_date" class="col-sm-5 control-label">Bone Marrow Shared Date</label>
                                <div class="input-prepend input-group col-sm-7">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="csv_bone_marrow_shared_date"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <label></label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="csv-modal-btn-yes">Upload</button>
                    <button type="button" class="btn btn-default" id="csv-modal-btn-no">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-mi-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Are you sure to delete the customer?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="delete-modal-btn-yes">Yes</button>
                    <button type="button" class="btn btn-default" id="delete-modal-btn-no">No</button>
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
    <!-- JQquery CSV Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/0.8.9/jquery.csv.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/products.js') }}"></script>
@endpush