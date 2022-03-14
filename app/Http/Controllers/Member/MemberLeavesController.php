<?php

namespace App\Http\Controllers\Member;

use App\EmployeeLeaveQuota;
use App\Helper\Reply;
use App\Http\Requests\Leaves\StoreLeave;
use App\Http\Requests\Leaves\UpdateLeave;
use App\Leave;
use App\LeaveType;
use App\Notifications\LeaveApplication;
use App\Notifications\NewLeaveRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\BuyerBusinessDetails;
use App\ClientDetails;
use Auth;
use DB;
class MemberLeavesController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaves';
        $this->pageIcon = 'icon-logout';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('leaves', $this->user->modules), 403);
            return $next($request);
        });
    }

    public function index()
    {
        $this->leaves = Leave::byUser($this->user->id);
        $this->leavesCount = Leave::byUserCount($this->user->id);
        $this->leaveTypes = LeaveType::byUser($this->user->id);
        $this->allowedLeaves = $this->user->leaveTypes->sum('no_of_leaves');
        $this->pendingLeaves = Leave::where('status', 'pending')
            ->where('user_id', $this->user->id)
            ->orderBy('leave_date', 'asc')
            ->get();
        $this->employeeLeavesQuota = $this->user->leaveTypes;

        return view('member.leaves.index', $this->data);
    }

    public function create()
    {
        $this->leaveTypes = EmployeeLeaveQuota::with('leaveType')
            ->where('no_of_leaves', '>', 0)
            ->where('user_id', $this->user->id)
            ->get();

        $this->leaves = Leave::where('user_id', $this->user->id)
            ->select('leave_date')
            ->where('status', 'approved')
            ->where('duration', '<>', 'half day')
            ->groupBy('leave_date')
            ->get();
        return view('member.leaves.create', $this->data);
    }

    public function store(StoreLeave $request)
    {
        if ($request->duration == 'multiple') {
            session(['leaves_duration' => 'multiple']);
            $dates = explode(',', $request->multi_date);
            foreach ($dates as $date) {
                $leave = new Leave();
                $leave->user_id = $request->user_id;
                $leave->leave_type_id = $request->leave_type_id;
                $leave->duration = $request->duration;
                $leave->leave_date = Carbon::parse($date)->format('Y-m-d');
                $leave->reason = $request->reason;
                $leave->status = $request->status;
                $leave->save();
                session()->forget('leaves_duration');
            }
        } else {
            $leave = new Leave();
            $leave->user_id = $request->user_id;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->duration = $request->duration;
            $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
            $leave->reason = $request->reason;
            $leave->status = $request->status;
            $leave->save();
        }

        return Reply::redirect(route('member.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    public function show($id)
    {
        $this->leave = Leave::findOrFail($id);
        return view('member.leaves.show', $this->data);
    }

    public function edit($id)
    {
        $this->leaveTypes = EmployeeLeaveQuota::with('leaveType')
            ->where('no_of_leaves', '>', 0)
            ->where('user_id', $this->user->id)
            ->get();
        $this->leave = Leave::findOrFail($id);
        $view = view('member.leaves.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function update(UpdateLeave $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->status = $request->status;
        $leave->save();

        return Reply::redirect(route('member.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    public function destroy($id)
    {
        Leave::destroy($id);
        return Reply::success('messages.leaveDeleteSuccess');
    }

    public function leaveAction(Request $request)
    {
        Leave::destroy($request->leaveId);

        return Reply::success(__('messages.leaveStatusUpdate'));
    }

    public function data()
    {
        $leaves = Leave::with('user', 'type')
            ->where('user_id', $this->user->id)
            ->get();
        return DataTables::of($leaves)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '';

                $action .= '<a href="javascript:;" onclick="getEventDetail(' . $row->id . ')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';

                if ($row->status == 'pending') {
                    $action .= '  <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return $action;
            })
            ->addColumn('type', function ($row) {
                return ucfirst($row->type->type_name);
            })
            ->editColumn('leave_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->leave_date)->format($this->global->date_format);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'approved') {
                    return '<label class="label label-success">' . ucfirst($row->status) . '</label>';
                } elseif ($row->status == 'pending') {
                    return '<label class="label label-warning">' . ucfirst($row->status) . '</label>';
                } else {
                    return '<label class="label label-danger">' . ucfirst($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
	 public function genericreport()
    {
		$userId = Auth::id();
        $this->business=count(ClientDetails::where('business_sale_flag','1')->where('agent_id',$userId)->get());
       
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();

        return view('member.leaves.genericreport', $this->data);
    }
	 public function getGenReport(Request $request)
    {
		 $userId = Auth::id();
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
        $leadlist =DB::table('buyer_business_details as bu')
        ->select('bu.id','buyerTable.company_name as buyerCompanyName','sellerTable.company_name as sellerCompanyName','sellerTable.business_name as sellerBusinessName','buUser.name as buyerAgiantName','sellerTable.business_value','buyerTable.name as buyer_name')
        ->join('client_details as buyerTable','bu.buyer_client_details_id','=','buyerTable.id')
        ->join('client_details as sellerTable','bu.seller_client_details_id','=','sellerTable.id')
        ->join('users as buUser','buUser.id','=','buyerTable.user_id')
        ->where('bu.status','1')
		 ->where('buyerTable.business_sale_flag','1')
		  ->where('sellerTable.business_sale_flag','1')
		->where('buyerTable.agent_id',$userId)
		->orWhere('sellerTable.agent_id',$userId)
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
       
     
        ->make(true);
        
       // var_dump($leadlist);die;
       // $leaves = $leavesList->groupBy('users.id')->get();

        
    }

}
