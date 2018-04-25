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
                    <tr>
                        <td><input type="checkbox" class="minimal"></td>
                        <td>UA-11111-1</td>
                        <td>test@email.com</td>
                        <td>test@email.com</td>
                        <td class="text-center">Street Team</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td class="text-center">2018-4-14</td>
                        <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</td>
                        <td class="text-center text-danger">
                            <button class="btn btn-xs btn-success">update</button>
                            <button class="btn btn-xs btn-info">note</button>
                            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
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