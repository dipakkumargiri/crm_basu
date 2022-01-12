@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.companies.index') }}">Seller Details</a></li>
                <li class="active">Seller Details</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">Seller Details</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="panel-body">
                         <div class="form-body">
                          
                            <hr>
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">File Upload(** Type Excel)</label>
                                        <input type="file" class="form-control" id="file_u" name="file_u"
                                               value="">
                                    </div>
                                </div>
                                
                            </div>   
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> @lang('app.save')</button>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
   
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#save-form').click(function () {
           
                $.easyAjax({
            url: '{{route('admin.saveSellerQuery')}}',
            type: "POST",
            container: '#createMethods',
            data:$('#createMethods').serialize(),
            redirect: true,
            success: function(response){
                console.log(response);
                
            }
        });
           
        });

    </script>

@endpush

