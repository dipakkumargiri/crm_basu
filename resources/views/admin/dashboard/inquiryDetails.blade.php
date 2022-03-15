@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>Inquiry Details</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.companies.index') }}">Inquiry Details</a></li>
                <li class="active">Inquiry Details</li>
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
<div class="panel-body b-all border-radius">
                            <div class="row">
                                
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-xs-3">
                                              </div>
                                        <div class="col-xs-9">
                                        </div>
                                    </div>
                                </div>

                                
                                    
                                   

                                </div>
                                
                            </div>
<div class="row">


<div class="col-xs-12">

    <section>
        <div class="sttabs tabs-style-line">

           

            <div class="content-wrap">
                <section id="section-line-1" class="show">
                    <div class="row">


                        <div class="col-xs-12">
                            <div class="white-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">@lang('modules.lead.'.'client_name') : <b>{{$client_data[0]->clint_name}}</b></label>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">@lang('modules.lead.'.'client_email') : <b>{{$client_data[0]->email}}</b></label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">@lang('modules.lead.'.'company_name') : <b>{{$client_data[0]->company_name}}</b></label>
                                       
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">@lang('modules.lead.'.'website') : <b>{{$client_data[0]->website}}</b></label>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" >@lang('modules.lead.'.'phone_number') : <b>{{$client_data[0]->mobile}}</b></label>
                                       
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" >@lang('modules.lead.'.'address') : <b>{{$client_data[0]->address}}</b></label>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">@lang('modules.lead.'.'message') : <b>{{$client_data[0]->Message}}</b></label>
                                        
                                    </div>
                                </div>
                            </div>
                            @if(!empty($client_data[0]->type_id))	
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                    @if($client_data[0]->type_id==1)
                                        <label for="exampleInputPassword1">@lang('modules.lead.'.'type_id') : <b>@lang('modules.lead.'.'buyer')</b></label>
                                    @else 
                                    <label for="exampleInputPassword1">@lang('modules.lead.'.'type_id') : <b>@lang('modules.lead.'.'seller')</b></label>   
                                    @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                                <hr>
                                </div>
                            </div>
                        </div>

                    </div>

                </section>
            </div><!-- /content -->
        </div><!-- /tabs -->
    </section>
</div>


</div>
<!-- .row -->

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

