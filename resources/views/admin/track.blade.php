@extends('adminlte::page')

@section('title', 'Pheramor - Track')

@section('content_header')
    <h1>{{ $product->pheramor_id }}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customers</li>
        <li class="active">Track</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="bs-vertical-wizard">
                <ul>
                    <li class="complete">
                        <a>Good Job <i class="ico fa fa-check ico-green"></i>
                            <span class="desc">Pheramor ID has been created at {{ $product->created_at }}</span>
                        </a>
                    </li>
                    @if(!empty($product->ship_date))
                    <li class="complete">
                        <a>Watch out <i class="ico fa fa-check ico-green"></i>
                            <span class="desc">Pheramor DNA kit has been shipped at {{ $product->ship_date }}</span>
                        </a>
                    </li>
                    @endif
                    <li class="@if ($product->swab_returned_date == null) current @else complete @endif">
                        <a>Swab Return
                            @if ($product->swab_returned_date != null)
                            <i class="ico fa fa-check ico-green"></i>
                            @endif
                            <span class="desc">Pheramor DNA Kit has came back at {{ $product->swab_returned_date }}.</span>
                        </a>
                    </li>
                    <li class="@if ($product->ship_to_lab_date == null) current @else complete @endif">
                        <a>Sequence
                            @if ($product->ship_to_lab_date != null)
                            <i class="ico fa fa-check ico-green"></i>
                            @endif
                            <span class="desc">It is under sequence now.</span>
                        </a>
                    </li>
                    <li class="@if ($product->uploaded_to_server_date == null) current @else complete @endif">
                        <a>Uploaded
                             @if ($product->uploaded_to_server_date != null)
                            <i class="ico fa fa-check ico-green"></i>
                            @endif
                            <!-- <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span> -->
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@stop

@push('css')
    <!-- custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/track.css') }}">
@endpush

@push('js')
    <!-- Custom JS -->
    <script src="{{ asset('js/track.js') }}"></script>
@endpush