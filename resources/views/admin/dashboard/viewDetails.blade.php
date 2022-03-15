@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>@lang('app.sellerBuyerDetails')</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.companies.index') }}">@lang('app.clientDetail')</a></li>
                <li class="active">@lang('app.clientDetail')</li>
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
                                    <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.fristName')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->frist_name}}</p>

                                    </div>
                                    <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.lastName')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->last_name}}</p>
                                    </div>
                                    <div class="col-md-4 col-xs-6"> <strong>@lang('app.organization')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->organization}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.address')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->address}}</p>

                                    </div>
                                    <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.email')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->email}}</p>
                                    </div>
                                    <div class="col-md-4 col-xs-6"> <strong>@lang('app.phoneNo')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->phonenumber}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('app.industry')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->industry}}</p>

                                    </div>
                                    <div class="col-md-6 col-xs-6 b-r"> <strong>@lang('app.noteComment')</strong> <br>
                                        <p class="text-muted">{{$client_data[0]->note}}</p>
                                    </div>
                                  
                                </div>
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

