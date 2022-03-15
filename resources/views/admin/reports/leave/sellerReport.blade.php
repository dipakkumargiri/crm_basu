@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>@lang("app.sellerReport")</h4>      
            </h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterange-picker/daterangepicker.css') }}" />

@endpush

@section('content')



    @section('filter-section')
        <div class="row">
            {!! Form::open(['id'=>'storePayments','method'=>'GET']) !!}
    <!--
            <div class="col-xs-12">
                <div class="example">
                    <h5 class="box-title m-t-20">@lang('app.selectDateRange')</h5>

                    <div class="form-group">
                        <div id="reportrange" class="form-control reportrange">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down pull-right"></i>
                        </div>

                        <input type="hidden" class="form-control" id="start-date" placeholder="@lang('app.startDate')"
                               value="{{ $fromDate->format($global->date_format) }}"/>
                        <input type="hidden" class="form-control" id="end-date" placeholder="@lang('app.endDate')"
                               value="{{ $toDate->format($global->date_format) }}"/>
                    </div>
                </div>
            </div>-->

            <div class="col-xs-12" id="type_d">
                <div class="example">
                    <h5 class="box-title m-t-20">@lang('app.reportType')</h5>

                    <div class="form-group">
                      <select class="form-control" id="reportType" name="reportType">
					   <option value="1">@lang('app.companyInformation')</option>
                      <option value="2">@lang('app.agentInformation')</option>
                       
                      </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 agent_in">
                <div class="example">
                    <h5 class="box-title m-t-20">@lang('app.agent')</h5>

                    <div class="form-group">
                      <select class="form-control" id="agent_id" name="agent_id">
                        <option value="">Select Agent</option>
                        @foreach($leadAgents as $emp)
                                                    <option value="{{ $emp->user_id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                      </select>
                    </div>
                </div>
            </div>

          <!--  <div class="col-xs-12">
                <h5 class="box-title m-t-20">@lang('app.reportType')</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <select class="select2 form-control"  id="reportType">
                              
                                <option value="1">@lang('app.leadDetails')</option>
                                <option value="2">@lang('app.sellerDetails')</option>
                                <option value="3">@lang('app.buyerDetails')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>-->

            <div class="col-xs-12">

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-6">
                          <input type="submit" id="filter-results" class="btn btn-success">
						  <!--<i class="fa fa-check"></i> @lang('app.apply')</button>-->
                        </div>
                        <div class="col-xs-6">
                            <button type="button" id="reset-filters" class="btn btn-inverse col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                        </div>
                    </div>
                   
                    </div>
                       
                  </div>
            {!! Form::close() !!}

        </div>
    @endsection

    <div class="row">
	
        <div class="col-lg-12">
            <div class="white-box">
                <h3 class="box-title">@lang("app.sellerReport")</h3>
                <div class="row">
                        
                <div class="row dashboard-stats front-dashboard" >
                 <div class="col-md-4 col-sm-6">
                <a href="https://anglobalindia.com/admin/projects">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-danger-gradient"><i class="icon-layers"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title">@lang("app.quarterly")</span><br>
                                <span class="counter">{{$quarterly}}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4 col-sm-6">
                <a href="https://anglobalindia.com/admin/projects">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-danger-gradient"><i class="icon-layers"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title">@lang("app.halfYearly")</span><br>
                                <span class="counter">{{$halfYearly}}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6">
                <a href="https://anglobalindia.com/admin/projects">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-xs-3">
                                <div>
                                    <span class="bg-danger-gradient"><i class="icon-layers"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-9 text-right">
                                <span class="widget-title">@lang("app.annually")</span><br>
                                <span class="counter">{{$annually}}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>           
        </div>

    </div> 
	
	
	
    <div class="panel panel-inverse">
	
                    <div class="panel-heading">@lang("app.agentTranction")</div>
					  <div class="row">
            <div class="col-xs-12">
                <div class="white-box">
                    <ul class="list-inline text-center m-t-40">
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color:rgb(13, 219, 228);"></i>@lang('app.earnings')
                            </h5>
                        </li>
                    </ul>
                    <div id="morris-area-chart" style="height: 340px;"></div>
                </div>
            </div>
        </div>

			<!--

                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                        <div class="row">
                             <div class="col-xs-6">
                                  <div class="panel-heading">@lang("app.sellerRelatedInformation")</div>
								  @if(count($sellerTranction ) >0)   
                                          <div class="steamline">
													@foreach($sellerTranction as $ever)
                                                        <div class="sl-item">
                                                                <div class="sl-left"><i class="fa fa-circle text-info"></i>
                                                                </div>
                                                                <div class="sl-right">
                                                                    <div><h6><a href="#" class="font-bold">{{$ever->name}}</a> @lang("app.transactionDate"): <b>{{$ever->created_at}}</b></h6></div>
                                                                </div>
                                                        </div>
													@endforeach	
                                             </div>
                                 @endif
                                            
                                  </div>  
                                 <div class="col-xs-6">
                                    <div class="panel-heading">@lang("app.buyerRelatedInformation")</div>   
									 @if(count($buyerTranction ) >0)  
                                          <div class="steamline">
									  @foreach($buyerTranction as $everBuyer)
                                                        <div class="sl-item">
                                                                <div class="sl-left"><i class="fa fa-circle text-info"></i>
                                                                </div>
                                                                <div class="sl-right">
                                                                    <div><h6><a href="#" class="font-bold">{{$everBuyer->name}}</a> @lang("app.transactionDate"): <b>{{$everBuyer->created_at}}</b></h6></div>
                                                                </div>
                                                        </div>
												@endforeach	
                                             </div>
						        	@endif
                                            
                                  </div>  
                                </div>     

                        </div>
                    </div>
					
					-->
                </div> 
 </div>
 </div>
</div>

    {{--// This form is using for post method in export leave --}}
    <form name="exportForm" id="exportForm" method="post" action="{{ route('admin.exportGenricReport') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="startDateField" id="startDateField" >
        <input type="hidden" name="endDateField" id="endDateField" >
        <input type="hidden" name="reportTypes" id="reportTypeS" >
    </form>
    {{--End Form--}}

    {{--// This form is using for post method in delete deals --}}
    <form name="deleteDealsFrom" id="deleteDealsFrom" method="post" action="{{ route('admin.deleteDeals') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="dealID" id="dealID">
    </form>
    {{--End Form--}}

   

    <div class="modal fade bs-example-modal-lg" id="leave-details" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@push('footer-script')

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterange-picker/daterangepicker.js') }}"></script>
    <script>
	
	$(document).ready(function () {
		var agentId='<?php echo $agent_id ?>';
		if(agentId){
			//alert(agentId);
			$('.agent_in').show();
			$('#reportType').val('2');
		}else{
			$('.agent_in').hide();
		}
		$('#agent_id').val(agentId);
		
            var chartData = {!!  $chartData !!};

            Morris.Area({
                element: 'morris-area-chart',
                data: chartData,
                lineColors: ['#01c0c8'],
                xkey: ['month'],
                ykeys: ['amount'],
                labels: ['Earning'],
                pointSize: 0,
                lineWidth: 0,
                resize: true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto',
                parseTime: false
            });

            $('.vcarousel').carousel({
                interval: 3000
            })
        });
	
        $(function() {
            var dateformat = '{{ $global->moment_format }}';

            var startDate = '{{ $fromDate->format($global->date_format) }}';
            var start = moment(startDate, dateformat);

            var endDate = '{{ $toDate->format($global->date_format) }}';
            var end = moment(endDate, dateformat);

            function cb(start, end) {
                $('#start-date').val(start.format(dateformat));
                $('#end-date').val(end.format(dateformat));
                $('#reportrange span').html(start.format(dateformat) + ' - ' + end.format(dateformat));
            }
            moment.locale('{{ $global->locale }}');
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,

                locale: {
                    language: '{{ $global->locale }}',
                    format: '{{ $global->moment_format }}',
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

        });
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        loadTable();
        $('#filter-results').click(function () {
            loadTable();
        });
        $('#reset-filters').click(function () {
           /* $('#storePayments')[0].reset();
            $('.select2').val('all');
            $('#storePayments').find('select').select2();
            $('#start-date').val('{{ $fromDate->format($global->date_format) }}');
            $('#end-date').val('{{ $toDate->format($global->date_format) }}');
            $('#reportrange span').html('{{ $fromDate->format($global->date_format) }}' + ' - ' + '{{ $toDate->format($global->date_format) }}');
            $('#filter-results').trigger("click");*/
			window.location.href="https://anglobalindia.com/admin/reports/sellerReport";
        })

        function loadTable(){
            //alert ('filter-results');
            var startDate = $('#start-date').val();
            var reportType =$('reportType').val();
            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var reportType = $('#reportType').val();
            if (!reportType) {
                reportType = 0;
            }

            var url = '{!!  route('admin.getGenReport') !!}';

            var table = $('#leave-table').dataTable({
                responsive: true,
                //processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    "url": url,
                    "type": "POST",
                    data: function (d) {
                       // console.log(d);
                        d.startDate = startDate;
                        d.endDate = endDate;
                        d.reportType = reportType;
                        d._token = '{{ csrf_token() }}';
                    }
                },
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function( oSettings ) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                  
                    { data: 'sellerCompanyName', name: 'sellerCompanyName' },
                    { data: 'buyerCompanyName', name: 'buyerCompanyName' },
                    { data: 'sellerBusinessName', name: 'sellerBusinessName' },
                    { data: 'businessValue', name: 'businessValue' },
                    { data: 'buyerAgiantName', name: 'buyerAgiantName' },
                    { data: 'action', name: 'action' }
                   
                ]
            });

        }

        $('#leave-table').on( 'click', '.view-approve', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.show', ':id') }}?startDate=' + startDate + '&endDate=' + endDate;
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });

        $('#leave-table').on( 'click', '.view-pending', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }
            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.pending-leaves', ':id') }}?startDate=' + startDate + '&endDate=' + endDate;
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });

        $('#leave-table').on( 'click', '.view-upcoming', function (event) {
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }
            event.preventDefault();
            var id = $(this).data('pk');
            var url = '{{ route('admin.leave-report.upcoming-leaves', ':id') }}?startDate=' + startDate + '&endDate=' + endDate;
            url = url.replace(':id', id);
            $.ajaxModal('#leave-details', url);
        });


        $('#exportUserData').click(function(){

            var id = $(this).data('pk');
            var startDate = $('#start-date').val();

            if(startDate == ''){
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if(endDate == ''){
                endDate = null;
            }
            $('#startDateField').val(startDate);
            $('#endDateField').val(endDate);
            $('#reportTypeS').val('0');

            // TODO:: Search a batter method for jquery post request
            $( "#exportForm" ).submit();

        });
        $('#leave-table').on( 'click', '.exportUserData', function (event) {
            var id = $(this).data('pk');
            $('#dealID').val(id);
            if (confirm('Are you sure you want to delete')) {
              //  alert('Thanks for confirming');
                $( "#deleteDealsFrom" ).submit();
            } 
                        
        });
        $('#bagent').click(function(){
            var id = $(this).data('pk');
            var startDate = $('#start-date').val();

            if(startDate == ''){
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if(endDate == ''){
                endDate = null;
            }
            $('#startDateField').val(startDate);
            $('#endDateField').val(endDate);
            $('#reportTypeS').val('1');

            // TODO:: Search a batter method for jquery post request
            $( "#exportForm" ).submit();
        });
        $('#sagent').click(function(){
            var id = $(this).data('pk');
            var startDate = $('#start-date').val();

            if(startDate == ''){
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if(endDate == ''){
                endDate = null;
            }
            $('#startDateField').val(startDate);
            $('#endDateField').val(endDate);
            $('#reportTypeS').val('2');

            // TODO:: Search a batter method for jquery post request
            $( "#exportForm" ).submit();
        });
        $('#reportType').change(function(){
            var reportType=$('#reportType').val();
            if(reportType=='2'){
                    $('.agent_in').show();
            }else{
                $('.agent_in').hide();
            }
        });
        
    </script>
@endpush