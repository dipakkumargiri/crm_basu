<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LeaveReportExport;
use App\Leave;
use App\LeaveType;
use App\User;
use Carbon\Carbon;
//use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Lead;
use App\BuyerBusinessDetails;
use App\Helper\Reply;
use App\LeadAgent;
use App\PaypalInvoice;
use App\PaystackInvoice;
use App\RazorpayInvoice;
use App\StripeInvoice;
use App\MollieInvoice;
use App\AuthorizationInvoice;
use App\ClientDetails;
class LeaveReportController extends AdminBaseController
{

    /**
     * LeaveReportController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaveReport';
        $this->pageIcon = 'ti-pie-chart';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('reports', $this->user->modules), 403);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();

        return view('admin.reports.leave.index', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $this->modalHeader = 'approved';
        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
            }
            $query->where('status', 'approved')->where('user_id', $id);
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason', 'leaves.duration')
            ->where('leaves.status', 'approved')
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;
        return view('admin.reports.leave.leave-detail', $this->data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        $startDate  = $request->startDate;
        $endDate    = $request->endDate;
        $employeeId = $request->employeeId;

        $startDt = '';
        $endDt = '';

        $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();

        if (!is_null($startDate)) {
            $startDt = 'and DATE(leaves.`leave_date`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDt = 'and DATE(leaves.`leave_date`) <= ' . '"' . $endDate . '"';
        }

        $leavesList = User::selectRaw(
            'users.id, users.name, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.status = \'approved\' ' . $startDt . ' ' . $endDt . ' ) as count_approved_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.status = \'approved\' ' . $startDt . ' ' . $endDt . ' ) as count_approved_half_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.status = \'pending\' ' . $startDt . ' ' . $endDt . ') as count_pending_leaves, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.status = \'pending\' ' . $startDt . ' ' . $endDt . ') as count_pending_half_leaves, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.leave_date > "' . Carbon::now()->format('Y-m-d') . '" and leaves.status != \'rejected\' ' . $startDt . ' ' . $endDt . ') as count_upcoming_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.leave_date > "' . Carbon::now()->format('Y-m-d') . '" and leaves.status != \'rejected\' ' . $startDt . ' ' . $endDt . ') as count_upcoming_half_leaves'
        )->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client');

        if ($employeeId != 0) {
            $leavesList->where('users.id', $employeeId);
        }

        $leaves = $leavesList->groupBy('users.id')->get();

        return DataTables::of($leaves)
            ->addColumn('employee', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('approve', function ($row) {
                return '<div class="label-success label">' . ($row->count_approved_leaves + ($row->count_approved_half_leaves) / 2) . '</div>
                <a href="javascript:;" class="view-approve" data-pk="' . $row->id . '">View</a>';
            })
            ->addColumn('pending', function ($row) {
                return '<div class="label-warning label">' . ($row->count_pending_leaves + ($row->count_pending_half_leaves) / 2) . '</div>
                <a href="javascript:;" data-pk="' . $row->id . '" class="view-pending">View</a>';
            })
            ->addColumn('upcoming', function ($row) {
                return '<div class="label-info label">' . ($row->count_upcoming_leaves + ($row->count_upcoming_half_leaves) / 2) . '</div>
                <a href="javascript:;" data-pk="' . $row->id . '" class="view-upcoming">View</a>';
            })
            ->addColumn('action', function ($row) {
                return '<a  href="javascript:;" data-pk="' . $row->id . '"  class="btn btn-info btn-sm exportUserData"
                      data-toggle="tooltip" data-original-title="Export to excel"><i class="ti-export" aria-hidden="true"></i> Export</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['approve', 'upcoming', 'pending', 'action'])
            ->make(true);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pendingLeaves(Request $request, $id)
    {
        $this->modalHeader = 'pending';

        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(`leave_date`)'), '<=', $endDate);
            }
            $query->where('status', 'pending')->where('user_id', $id);
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason')
            ->where('leaves.status', 'pending')
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;


        return view('admin.reports.leave.leave-detail', $this->data);
    }

    /**x
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upcomingLeaves(Request $request, $id)
    {
        $this->modalHeader = 'upcoming';

        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
            }
            $query->where('user_id', $id)->where(function ($q) {
                $q->where('leaves.status', 'pending')
                    ->orWhere('leaves.status', 'approved');
            })->where('leave_date', '>', Carbon::now()->format('Y-m-d'));
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason')
            ->where(function ($q) {
                $q->where('leaves.status', 'pending')
                    ->orWhere('leaves.status', 'approved');
            })
            ->where('leaves.leave_date', '>', Carbon::now()->format('Y-m-d'))
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;

        return view('admin.reports.leave.leave-detail', $this->data);
    }

    public function export(Request $request)
    {
        $id         = $request->leaveID;
        $startDate  = Carbon::createFromFormat($this->global->date_format, $request->startDateField)->toDateString();
        $endDate    = Carbon::createFromFormat($this->global->date_format, $request->endDateField)->toDateString();

        $employees  = User::find($id);
        // Generate and return the spreadsheet
        return Excel::download(new LeaveReportExport($id, $startDate, $endDate), $employees->name . ' Leaves.xlsx');
    }
    public function genericreport()
    {
        $this->business=count(BuyerBusinessDetails::where('status','1')->get());
       
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();

        return view('admin.reports.leave.genericreport', $this->data);
    }
    public function getGenReport(Request $request)
    {
        $startDate  = $request->startDate;
        $endDate    = $request->endDate;
        $reportType = $request->reportType;

        $startDt = '';
        $endDt = '';

        $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();
        //echo $startDate." ".$endDate;die;
        if (!is_null($startDate)) {
            $startDt = 'and DATE(`bu.created_at`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDt = 'and DATE(`bu.created_at`) <= ' . '"' . $endDate . '"';
        }

       /* if($reportType== '1') {
            $leadlist =Lead::select()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('client_id', '0')
            ->get();

        }else if($reportType== '2'){
            $leadlist =Lead::select()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('client_type', '2')
            ->get();
        }else if($reportType== '3'){
            $leadlist =Lead::select()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('client_type', '1')
            ->get();
        }else{
            $leadlist =Lead::select()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('client_id', '0')
            ->get();

        }*/
        $leadlist =DB::table('buyer_business_details as bu')
        ->select('bu.id','buyerTable.company_name as buyerCompanyName','sellerTable.company_name as sellerCompanyName','sellerTable.business_name as sellerBusinessName','buUser.name as buyerAgiantName','sellerTable.business_value','buyerTable.name as buyer_name')
        ->join('client_details as buyerTable','bu.buyer_client_details_id','=','buyerTable.id')
        ->join('client_details as sellerTable','bu.seller_client_details_id','=','sellerTable.id')
        ->join('users as buUser','buUser.id','=','buyerTable.user_id')
        ->where('bu.status','1')
        ->get();
        return DataTables::of($leadlist)
        ->addColumn('sellerCompanyName', function ($row) {
            return ucwords($row->sellerCompanyName);
        })
        ->addColumn('buyerCompanyName', function ($row) {
            return ucwords($row->buyer_name);
        })
        ->addColumn('sellerBusinessName', function ($row) {
            return ucwords($row->sellerBusinessName);
        })
        ->addColumn('businessValue', function ($row) {
            return ucwords($row->business_value);
            
        })
        ->addColumn('buyerAgiantName', function ($row) {
            return ucwords($row->buyerAgiantName);
        })
    
    ->addColumn('action', function ($row) {
        return '<a  href="javascript:;" data-pk="' . $row->id . '"  class="btn btn-info btn-sm exportUserData"
              data-toggle="tooltip" data-original-title="Delete"> Delete</a>';
    })
        
        ->make(true);
        
       // var_dump($leadlist);die;
       // $leaves = $leavesList->groupBy('users.id')->get();

        
    }

    public function exportGenricReport(Request $request){
        
        $startDate  = $request->startDateField;
        $endDate    = $request->endDateField;
        $reportType = $request->reportTypes;

        $startDt = '';
        $endDt = '';
        $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();
        //echo $startDate." ".$endDate;die;
        if (!is_null($startDate)) {
            $startDt = 'and DATE(`bu.created_at`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDt = 'and DATE(`bu.created_at`) <= ' . '"' . $endDate . '"';
        }

        $leadlist =DB::table('buyer_business_details as bu')
        ->select('bu.id','buyerTable.company_name as buyerCompanyName','sellerTable.company_name as sellerCompanyName','sellerTable.business_name as sellerBusinessName','buUser.name as buyerAgiantName','suUser.name as sellerAgiantName','buyerTable.agent_commission as buyeragentComission','sellerTable.agent_commission as selleragentComission','bu.created_at','sellerTable.business_value'
                ,'buUser.email as buyer_email','buUser.mobile as buyer_mobile','buUser.last_login as buyer_last_login',
                'suUser.email as seller_email','suUser.mobile as seller_mobile','suUser.last_login as seller_last_login',
                'buyerTable.name as buyer_name'
        )
        ->join('client_details as buyerTable','bu.buyer_client_details_id','=','buyerTable.id')
        ->join('client_details as sellerTable','bu.seller_client_details_id','=','sellerTable.id')
        ->join('users as buUser','buUser.id','=','buyerTable.user_id')
        ->join('users as suUser','suUser.id','=','sellerTable.user_id')
        ->where('bu.status','1')
        ->get();

        $filename = "deal_".date('Y-m-D').'.csv';
        header("Content-Type: application/xls");    
        header("Content-Disposition: attachment; filename=$filename");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if($reportType=='0'){
                    echo '"Seller Organization Name",' . '"Buyer  Name",' . '"Seller Business Name",' . '"Business Value",' .'"Buyer Agent Name",' .'"Seller Agent Name",' .'"Buyer Agent Comission(%)",'.'"Seller Agent Comission(%)",'.'"Deal Created Date",' ."\r\n";
                
                    foreach($leadlist as $x => $data){
                    
                        echo '"'.$data->sellerCompanyName.'",' . '"'.$data->buyer_name.'",' . '"'.$data->sellerBusinessName.'",' . '"'.$data->business_value.'",' .'"'.$data->buyerAgiantName.'",' .'"'.$data->sellerAgiantName.'",' .'"'.$data->buyeragentComission.'",' .'"'.$data->selleragentComission.'",'.'"'.$data->created_at.'",' ."\r\n";
                    }
         }else if($reportType=='1'){
            echo  '"Organization Name",'.'"Agent Name",' .'"Email",'.'"Mobile",'.'"Comission(%)",'.'"Last Login",' ."\r\n";
                
            foreach($leadlist as $x => $data){
            
                echo  '"'.$data->buyerCompanyName.'",'  .'"'.$data->buyerAgiantName.'",' .'"'.$data->buyer_email.'",' .'"'.$data->buyer_mobile.'",' .'"'.$data->buyeragentComission.'",'.'"'.$data->buyer_last_login.'",' ."\r\n";
            }
         }else if($reportType=='2'){
            echo  '"Organization Name",'.'"Business Name",'.'"Agent Name",' .'"Email",'.'"Mobile",'.'"Comission(%)",'.'"Last Login",' ."\r\n";
                
            foreach($leadlist as $x => $data){
            
                echo  '"'.$data->sellerCompanyName.'",'  .'"'.$data->sellerBusinessName.'",' .'"'.$data->sellerAgiantName.'",' .'"'.$data->seller_email.'",' .'"'.$data->seller_mobile.'",' .'"'.$data->selleragentComission.'",'.'"'.$data->seller_last_login.'",' ."\r\n";
            }
         }
    }
    function deleteDeals(Request $request){
        $details=BuyerBusinessDetails::where('id', '=', $request->input('dealID'))->first(); 
         $buyer_client_details_id=$details->buyer_client_details_id;
         $seller_client_details_id=$details->seller_client_details_id;
        DB::table('client_details')
        ->where('id', $seller_client_details_id)
        ->update(['business_sale_flag' => '0']);
        DB::table('client_details')
        ->where('id', $buyer_client_details_id)
        ->update(['business_sale_flag' => '0']);
        DB::table('buyer_business_details')
        ->where('id', $request->input('dealID'))
        ->update(['status' => '0']);
        return redirect(route('admin.genericreport'));
       }

    function sellerReport(Request $request){
        $this->fromDate = Carbon::today()->subDays(30);
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->toDate = Carbon::today();
		$this->annually=0;	
		$this->halfYearly=0;
		$this->quarterly=0;
		$this->agent_id='';
		$this->sellerTranction=array();
		$this->buyerTranction=array();
		 $months = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];
		  
		  $sumArray = array();
		  $this->chartData = json_encode($sumArray);   
		  $agent_id  = $request->agent_id;
		  $reportType= !empty($request->reportType)?$request->reportType:1;
		  if($reportType=='1'){
				$this->agent_id=$agent_id;
						$tranction= ClientDetails::select(
										DB::raw("(sum(business_value)) as amount"),
										DB::raw("MONTHNAME(created_at) as month")
									)
									->where('business_sale_flag','1')
									->whereYear('created_at', date('Y'))
									->groupBy('month')
									->orderBy('month', 'DESC')
									->get()
									->toArray();
					if(!empty($tranction)){
							/*echo "<pre>";
							print_r($tranction);
							die;*/
								$this->chartData = json_encode($tranction);	
							}else{
								$sumArray = array();
								$this->chartData = json_encode($sumArray);
							}
					//Quarterly		
								$dateS = Carbon::now()->startOfMonth()->subMonth(3);
							$dateE = Carbon::now()->startOfMonth(); 
							$this->quarterly = ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateS,$dateE])
							->where('business_sale_flag','1')
							//->where('agent_id',$agent_id)
							->sum('business_value');
							
							
							
							//halfYearly		
								$dateH = Carbon::now()->startOfMonth()->subMonth(6);
							$this->halfYearly = ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateH,$dateE])
							->where('business_sale_flag','1')
							//->where('agent_id',$agent_id)
							->sum('business_value');
							
							//Annualy		
								$dateA = Carbon::now()->startOfMonth()->subMonth(12);
							$this->annually =  ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateH,$dateE])
							->where('business_sale_flag','1')
						//	->where('agent_id',$agent_id)
							->sum('business_value');
							//var_dump($this->annually);die;			
		  }else{
					if(!empty($agent_id)){
						$this->agent_id=$agent_id;
						$tranction= ClientDetails::select(
										DB::raw("(sum(business_value)) as amount"),
										DB::raw("MONTHNAME(created_at) as month")
									)
									->where('agent_id',$agent_id)
									->where('business_sale_flag','1')
									->whereYear('created_at', date('Y'))
									->groupBy('month')
									->orderBy('month', 'DESC')
									->get()
									->toArray();
			  
									
						if(!empty($tranction)){
							/*echo "<pre>";
							print_r($tranction);
							die;*/
								$this->chartData = json_encode($tranction);	
							}else{
								$sumArray = array();
								$this->chartData = json_encode($sumArray);
							}			
							//Quarterly		
								$dateS = Carbon::now()->startOfMonth()->subMonth(3);
							$dateE = Carbon::now()->startOfMonth(); 
							$this->quarterly = ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateS,$dateE])
							->where('agent_id',$agent_id)
							->where('business_sale_flag','1')
							->sum('business_value');
							
							
							//halfYearly		
								$dateH = Carbon::now()->startOfMonth()->subMonth(6);
							$this->halfYearly = ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateH,$dateE])
							->where('agent_id',$agent_id)
							->where('business_sale_flag','1')
							->sum('business_value');
							
							
							//Annualy		
								$dateA = Carbon::now()->startOfMonth()->subMonth(12);
							$this->annually =  ClientDetails::select('business_value')
							->whereBetween('created_at',[$dateH,$dateE])
							->where('agent_id',$agent_id)
							->where('business_sale_flag','1')
							->sum('business_value');
							//var_dump($this->annually);die;
					}
		  }
        return view('admin.reports.leave.sellerReport', $this->data);
    }
}
