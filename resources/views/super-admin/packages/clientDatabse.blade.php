@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"> Client Data Base
                <span class="text-info b-l p-l-10 m-l-5"></span> <span
                        class="font-12 text-muted m-l-5">Client Data Base</span>
            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            <a href="{{ route('super-admin.packages.addDatabase') }}"
               class="btn btn-outline btn-info btn-sm btn-success">Add New<i
               class="fa fa-plus" aria-hidden="true"></i></a>
               <a href="{{ route('super-admin.packages.databaseExport') }}"
               class="btn btn-outline btn-info btn-sm">Export<i
               class="fa fa-download" aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="{{ route('super-admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">Package</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
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
    </style>
@endpush

@section('content')

    <div class="row">
       
        <div class="col-xs-12">
            <div class="white-box">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable display nowrap"
                           id="users-table">
                        <thead>
                        <tr>
                            <th>Frist Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($client_data as $client)
                            <tr>
                                     <th>{{$client->frist_name}}</th>
                                     <th>{{$client->last_name}}</th>
                                     <th>{{$client->email}}</th>
                                     <th>{{$client->phonenumber}}</th>
                                     <th>{{$client->note}}</th>
                                     <th><a title="View" href="{{ route('super-admin.packages.viewDetails',$client->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                     <a href="{{ route('super-admin.packages.deleteClent',$client->id) }}" onclick="return confirm('Are you sure?')" title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>
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
@endpush
