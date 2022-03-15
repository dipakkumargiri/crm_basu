
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"> Inquiry Lists
                <span class="text-info b-l p-l-10 m-l-5"></span> <span
                        class="font-12 text-muted m-l-5">Inquiry Lists </span>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Company Name</th>
                            <th>Lead Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiry_data as $client)
                            <tr>
                                     <th>{{$client->clint_name}}</th>
                                     <th>{{$client->email}}</th>
                                     <th>{{$client->mobile}}</th>
                                     <th>{{$client->company_name}}</th>
                                     @if ($client->type_id==1)
                                     <th>Buyer</th>
                                     @else
                                     <th>Seller</th>
                                     @endif
                                     <th><a title="View" href="{{ route('super-admin.packages.inquiryDetails',$client->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                     
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
