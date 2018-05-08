@extends('adminlte::page')

@section('title', 'Pheramor - Tags')

@section('content_header')
    <h1>Tags</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Tags</li>
    </ol>
@stop

@section('content')
    <div class="box box-info">
        <form  class="form-horizontal" id="tag_form">
        @if($tags->count() > 0)
            <div class="box-body">
            @foreach ($tags as $key => $tag)
                <div class="form-group">
                    <label for="{{ $tag->selector }}" class="col-sm-4 col-md-3 col-lg-2 control-label">{{ $tag->name }}</label>

                    <div class="col-sm-8 col-md-9 col-lg-10">
                        <input type="text" class="form-control" id="{{ $tag->selector }}" value="{{ $tag->value }}">
                    </div>
                </div>
            @endforeach
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="button" class="btn btn-info" id="btn_submit">Save</button>
            </div>
            <!-- /.box-footer -->
        @else
            No Record Found
        @endif
        </form>
    </div>
    <!-- /.box -->

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
    <!-- custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tags.css') }}">
@endpush

@push('js')
    <!-- Custom JS -->
    <script src="{{ asset('js/tags.js') }}"></script>
@endpush