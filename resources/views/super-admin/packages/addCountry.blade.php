@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> Add Country</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            
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
                <div class="panel-heading">Add Country</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="panel-body">
                         <div class="form-body">
                          
                            <hr>
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Currency Name</label>
                                        <input type="text" class="form-control" id="currency_name" name="currency_name"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Currency Code</label>
                                        <input type="text" class="form-control" id="currency_code" name="currency_code"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone">Dialing Code</label>
                                        <input type="text" class="form-control" id="dialing_code" name="dialing_code"
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
            var name=$('#name').val(); 
            if(!name){ 
                swal("Please Enter Name!");
            }else{
                $.easyAjax({
            url: '{{route('super-admin.companies.saveCountry')}}',
            type: "POST",
            container: '#createMethods',
            data:$('#createMethods').serialize(),
            redirect: true,
            success: function(response){
               // console.log(response);
                
                if(response.status=='success'){
                    swal("Data Saved Successfully!");
                    window.location.href = "/super-admin/countryList";
                    //window.location.reload();
                }else if(response.status=='Faild'){
                    swal(response.msg);
                }
            }
        });
            }
        });

        
    </script>

@endpush

