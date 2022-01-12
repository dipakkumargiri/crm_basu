@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> Add Client Database</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('super-admin.companies.index') }}">Add Client Database</a></li>
                <li class="active">Add Client Database</li>
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
                <div class="panel-heading"> Add Client Databe</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}
                    <div class="panel-body">
                         <div class="form-body">
                          
                            <hr>
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Frist Name</label>
                                        <input type="text" class="form-control" id="f_name" name="f_name"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Last Name</label>
                                        <input type="text" class="form-control" id="l_name" name="l_name"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Organization</label>
                                        <input type="text" class="form-control" id="org" name="org"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1" class="required">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_phone" class="required">Industry</label>
                                        <input type="text" class="form-control" id="industry" name="industry"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Note/Comment</label>
                                        <input type="text" class="form-control" id="note" name="note"
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
            var f_name=$('#f_name').val();
            var l_name=$('#l_name').val();
            var org=$('#org').val();
            var address=$('#address').val();
            var email=$('#email').val();
            var phone=$('#phone').val();
            var industry=$('#industry').val();
            var note=$('#note').val();
            //console.log('ffffffffff');
           
            if(!f_name){ 
                swal("Please Enter Frist Name!");
            }else if(!l_name){
                swal("Please Enter Last Name!");
            }else if(!org){
                swal("Please Enter Organization!");
            }else if(!address){
                swal("Please Enter Address!");
            }else if(!email){
                swal("Please Enter Email!");
            }else if(email && IsEmail(email)==false){
                swal("Please Enter Valid Email!");
            }
            else if(!phone){
                swal("Please Enter Phone Number!");
            }
            else if(!industry){
                swal("Please Enter Industry!");
            }else{
                $.easyAjax({
            url: '{{route('super-admin.companies.saveClientDataBase')}}',
            type: "POST",
            container: '#createMethods',
            data:$('#createMethods').serialize(),
            redirect: true,
            success: function(response){
               // console.log(response);
                
                if(response.status=='success'){
                    swal("Data Saved Successfully!");
                    window.location.href = "/super-admin/clientDatabse";
                    //window.location.reload();
                }else if(response.status=='Faild'){
                    swal(response.msg);
                }
            }
        });
            }
        });

        function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }
    </script>

@endpush

