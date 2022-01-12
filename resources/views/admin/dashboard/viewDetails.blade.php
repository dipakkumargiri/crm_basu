@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>Client Details</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.companies.index') }}">Client Details</a></li>
                <li class="active">Client Details</li>
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
                <div class="panel-heading"> Client Details</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                
                    <div class="panel-body">
                         <div class="form-body">
                          
                            <hr>
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Frist Name:<b>{{$client_data[0]->frist_name}}</b></label>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Last Name:<b>{{$client_data[0]->last_name}}</b></label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" >Organization:<b>{{$client_data[0]->organization}}</b></label>
                                      
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Address:<b>{{$client_data[0]->address}}</b></label>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" >Email:<b>{{$client_data[0]->email}}</b></label>
                                       
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" >Phone Number:<b>{{$client_data[0]->phonenumber}}</b></label>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Industry:<b>{{$client_data[0]->industry}}</b></label>
                                       
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Note/Comment:<b>{{$client_data[0]->note}}</b></label>
                                        
                                    </div>
                                </div>
                            </div>

                           
                            
                        </div>
                       
                     
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

        

      
    </script>

@endpush

