@extends('layouts.app')

@section('page-title')
      <div class="row bg-title">
        <div class="col-lg-8 col-md-5 col-sm-6 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="icon-people"></i> @lang('app.client_database')
                <span class="text-info b-l p-l-10 m-l-5">{{ $totalLeads }}</span> <span
                        class="font-12 text-muted m-l-5">@lang('app.total_client_database')</span>
            </h4>
        </div>
    </div>
@endsection
   
@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">


    <style>
        ul{
            list-style-type:none;
        }
        .fa-check{
            color:green;
        }
        .fa-times{
            color:red;
        }
        .dark-text {
            background: #686868 !important;
            color: #ffffff !important;
            border-radius: 50%;
            font-size: 12px;
            padding: 5px;
            width: 32px;
            height: 32px;
            display: inline-block !important;
            text-align: center;

            .btn-primary1, .btn-primary.disabled {
            background: #4CAF50 ;
            border: 1px solid #4CAF50 ;
        }
    </style>
@endpush

@section('content')

    <div class="row">
       
        <div class="col-xs-12">
            <div class="white-box">


                <div class="table-responsive">
                <button style="margin-bottom: 10px" disabled class="btn btn-primary1 delete_all" id="deleteAll" data-url="{{ url('admin/deleteAllClientDatabase') }}">Delete All</button>
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable display nowrap"
                           id="users-table">
                        <thead>
                        <tr>
                            <th width="50px"><input type="checkbox" id="master"></th>
                            <th>@lang('app.fristName')</th>
                            <th>@lang('app.lastName')</th>
                            <th>@lang('app.email')</th>
                            <th>@lang('app.phoneNo')</th>
                            <th>@lang('app.comment')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($client_data as $client)
                            <tr id="tr_{{$client->id}}">
                                     <th><input type="checkbox" class="sub_chk" data-id="{{$client->id}}"></th>
                                     <th>{{$client->frist_name}}</th>
                                     <th>{{$client->last_name}}</th>
                                     <th>{{$client->email}}</th>
                                     <th>{{$client->phonenumber}}</th>
                                     <th>{{$client->note}}</th>
                                     <th><a title="View" href="{{ route('admin.viewDetails',$client->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                     <a href="{{ route('admin.deleteClent',$client->id) }}" onclick="return confirm('Are you sure?')" title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                     </th>
                                     </tr>
                            @endforeach
                       
                        </tbody>
                    </table>
                </div>
              
            </div>

        </div>
    </div>
    <!-- .row -->
    {!! Form::open(['id'=>'ajaxClient','class'=>'ajax-form','method'=>'POST']) !!}
        <input type="hidden" name="all_id" id="allId">
    {!! Form::close() !!}
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src=" https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
   
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>




    <script>
      $(document).ready(function() {
    $('#users-table').DataTable( {
    } );{{}}
} );
    </script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true); 
            $("#deleteAll").prop('disabled', false); 
            $("#deleteAll").css('background-color', '#8b2323'); 
            $("#deleteAll").css('color', '#ffffff');

         } else {  
            $("#master").prop('checked', false); 
            $(".sub_chk").prop('checked', false);  
            $("#deleteAll").prop('disabled', true); 
            $("#deleteAll").css('background-color', ''); 
            $("#deleteAll").css('color', '');
         }  
        });


      $("#master").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
      });
      $('.sub_chk').on('click', function () {
            if ($('.sub_chk:checked').length == $('.sub_chk').length) {
            $('#master').prop('checked', true);
            } else {
            $('#master').prop('checked', false);
            }
      });
   


        $('.delete_all').on('click', function(e) {
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  
            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  
                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  
                    var join_selected_values = allVals.join(","); 
                    $('#allId').val(join_selected_values);
                    $.easyAjax({
                          url: $(this).data('url'),
                            container: '#ajaxClient',
                            type: "POST",
                            redirect: true,
                            data: $('#ajaxClient').serialize(),
                            success: function (data) {

                            if (data['status']==true) {
                                alert(data['message']);
                                location.reload();
                                $("#master").prop('checked', false);
                                $(".sub_chk").prop('checked', false); 

                            } else {

                                alert('Whoops Something went wrong!!');

                            }

                            },

                            error: function (data) {

                            alert(data.responseText);

                            }

                           })
                 
                  
                }  
            }  
        });
        
    });

</script>

@endpush
