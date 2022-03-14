<?php

namespace App\Http\Controllers\Member;

use App\ClientDetails;
use App\Helper\Reply;
use App\Http\Controllers\Admin\ManageClientsController;
use App\Http\Requests\Admin\Client\MemberUpdateClientRequest;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Invoice;
use App\Lead;
use App\Notifications\NewUser;
use App\Role;
use App\Scopes\CompanyScope;
use App\User;
use App\ClientCategory;
use App\ClientSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Project;
use App\ContractType;
use App\Country;
use App\DataTables\Member\SellersDataTableMember;
use App\DataTables\Member\BuyerDataTableMember;
use App\ClientDocs;
use App\Http\Requests\EmployeeDocs\CreateRequest;
use App\Helper\Files;
use Illuminate\Support\Facades\File;
use App\CogCountry;
use App\LeadAgent;
use App\DataTables\Admin\BuyerDataTable;
use Auth;
use App\LeadStatus;
use App\LeadStage;
class MemberClientsController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';

        $this->middleware(function ($request, $next) {
            abort_if(!in_array('clients', $this->user->modules), 403);
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
        abort_if(!$this->user->cans('view_clients'), 403);
        $this->clients = User::allClients();

        return view('member.clients.index', $this->data);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        if ($leadID) {
            $this->leadDetail = Lead::findOrFail($leadID);
           // echo "<pre>";
           // print_r($this->leadDetail);die;
            $this->leadName = $this->leadDetail->client_name;
            $source_id=!empty($leadDetail->source_id)?$leadDetail->source_id:Null;
            $status_id=!empty($leadDetail->status_id)?$leadDetail->status_id:Null;
            $stage_id=!empty($leadDetail->stage_id)?$leadDetail->stage_id:Null;
            if(!empty($source_id)){
                $this->sourceDetails=DB::table('lead_sources')->where('id',$source_id)->first();
            }else{
                $this->sourceDetails=array();
            }
            if(!empty($status_id)){
                $this->statusDetails=DB::table('lead_status')->where('id',$status_id)->first();
            }else{
                $this->statusDetails=array();
            }
            if(!empty($stage_id)){
                $this->stageDetails=DB::table('lead_stages')->where('id',$stage_id)->first();
            }else{
                $this->stageDetails=array();
            }
           // var_dump($this->statusDetails);die;
            $this->firstName = '';
            $firstNameArray = ['mr','mrs','miss','dr','sir','madam'];
            $firstName = explode(' ', $this->leadDetail->client_name);
            if(isset($firstName[0]) && (array_search($firstName[0], $firstNameArray) !== false))
            {
                $this->firstName = $firstName[0];
                $this->leadName = str_replace($this->firstName, '', $this->leadDetail->client_name);
            }
            if($this->leadDetail->mobile){
                $this->code = explode(' ', $this->leadDetail->mobile);
                $this->mobileNo = str_replace($this->code[0], '', $this->leadDetail->mobile);
            }
        }


        $client = new ClientDetails();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;
        $this->countries = Country::all();
        $this->Allcountries = CogCountry::all();
        $this->leadAgents = LeadAgent::with('user')->get();
        if (request()->ajax()) {
            return view('admin.clients.ajax-create', $this->data);
        }
        
        return view('member.clients.create', $this->data);
    }
  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        
        $isSuperadmin = User::withoutGlobalScopes(['active', CompanyScope::class])->where('super_admin', '1')->where('email', $request->input('email'))->get()->count();
        if ($isSuperadmin > 0) {
            return Reply::error(__('messages.superAdminExistWithMail'));
        }
        
        $existing_user = User::withoutGlobalScopes(['active', CompanyScope::class])->select('id', 'email')->where('email', $request->input('email'))->first();
        // echo "<pre>"; echo $existing_user;exit;
        $new_code = Country::select('phonecode')->where('id', $request->phone_code)->first();
        
        // if no user found create new user with random password
        if (!$existing_user) {
            
            // $password = str_random(8);
            // create new user
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->mobile = ($new_code != null) ? $new_code->phonecode.' '.$request->input('mobile') : '';
            $user->country_id = $request->input('phone_code');

            if ($request->has('lead')) {
                $user->country_id = $request->input('country_id');
            }
            if($request->input('locale') != ''){
                $user->locale = $request->input('locale');
            }else{
                $user->locale = company()->locale;

            }
            $user->save();

            // attach role
            $role = Role::where('name', 'client')->first();
            $user->attachRole($role->id);

            if ($request->has('lead')) {
                $lead = Lead::findOrFail($request->lead);
                $lead->client_id = $user->id;
                $lead->save();
            }
        }
        
        
        $existing_client_count = ClientDetails::select('id', 'email', 'company_id')
            ->where(
                [
                    'email' => $request->input('email')
                ]
            )->count();
        // echo $existing_user->id."==".$user->id; 
        // exit;
        if ($existing_client_count === 0) {
            
            $client = new ClientDetails();
            $client->user_id = $existing_user ? $existing_user->id : $user->id;
            $client->name = $request->salutation.' '.$request->input('name');
            $client->email = $request->input('email');
            $client->mobile = ($new_code != null) ? $new_code->phonecode.' '.$request->input('mobile') : ' ';
            $client->office_phone = $request->input('office_phone');
            $client->city = $request->input('city_id');
            $client->state = $request->input('state_id');
            $client->postal_code = $request->input('postal_code');
            $client->country_id = $request->input('cog_countries_id');
            $client->category_id = ($request->input('category_id') != 0 && $request->input('category_id') != '') ? $request->input('category_id') : null;
            $client->sub_category_id = ($request->input('sub_category_id') != 0 && $request->input('sub_category_id') != '') ? $request->input('sub_category_id') : null;
            $client->company_name = $request->company_name;
            $client->address = $request->address;
            $client->website = $request->hyper_text.''.$request->website;
            $client->note = $request->note;
            $client->skype = $request->skype;
            $client->facebook = $request->facebook;
            $client->twitter = $request->twitter;
            $client->linkedin = $request->linkedin;
            $client->gst_number = $request->gst_number;
            $client->shipping_address = $request->shipping_address;
           // $client->lead_source = $request->input('cog_countries_id');
            $client->lead_stage = $request->input('stages');
            $client->lead_status = $request->input('status');
           // $client->agent_id = $request->input('agent_id');
            $client->type = $request->input('client_type');    
            if ($request->has('email_notifications')) {
                $client->email_notifications = $request->email_notifications;
            }
            // echo "<pre>"; print_r($client);exit;
            $client->save();
            $created_by=date('Y-m-d');
            DB::table('client_database')->insert(
                array(
                       'frist_name' => $request->salutation, 
                       'last_name' => $request->input('name'),
                       'organization'=>$request->company_name,
                       'address'=> $request->address,
                       'email'=> $request->input('email'),
                       'phonenumber' =>$request->input('office_phone'),
                       'note'=>$request->note,
                       'created_at' =>Auth::user()->id,
                       'created_by'=>$created_by
                )
           );
            
            // attach role
            if ($existing_user) {
                $role = Role::where('name', 'client')->where('company_id', $client->company_id)->first();
                $existing_user->attachRole($role->id);
            }

            // To add custom fields data
            if ($request->get('custom_fields_data')) {
                $client->updateCustomFieldData($request->get('custom_fields_data'));
            }

            // log search
            if (!is_null($client->company_name)) {
                $user_id = $existing_user ? $existing_user->id : $user->id;
                $this->logSearchEntry($user_id, $client->company_name, 'admin.clients.edit', 'client');
            }
            //log search
            $this->logSearchEntry($client->id, $request->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $request->email, 'admin.clients.edit', 'client');
        } else {
            return Reply::error('Provided email is already registered. Try with different email.');
        }

        if (!$existing_user && $request->sendMail == 'yes') {
            //send welcome email notification
            $user->notify(new NewUser($user->password));
        }

        if ($request->has('ajax_create')) {
            $teams = User::allClients();
            $teamData = '';

            foreach ($teams as $team) {
                $teamData .= '<option value="' . $team->id . '"> ' . ucwords($team->name) . ' </option>';
            }

            return Reply::successWithData(__('messages.clientAdded'), ['teamData' => $teamData]);
        }

      if($request->input('client_type')=='1'){
        return Reply::redirect(route('member.buyer'));
      }else{
        return Reply::redirect(route('member.seller'));
      }
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(!$this->user->cans('view_clients'), 403);

        $this->client = User::findClient($id);
        return view('member.clients.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!$this->user->cans('edit_clients'), 403);

        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->id)->first();
        $clientWebsite = new ManageClientsController();
        $this->clientWebsite = $clientWebsite->websiteCheck($this->clientDetail->website);
        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        return view('member.clients.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MemberUpdateClientRequest $request, $id)
    {
        $client = ClientDetails::where('user_id', '=', $id)->first();

        $client->company_name = $request->company_name;
        $client->address = $request->address;
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->mobile = $request->input('mobile');
        $client->website = $request->hyper_text.''.$request->website;
        $client->note = $request->note;
        $client->skype = $request->skype;
        $client->facebook = $request->facebook;
        $client->twitter = $request->twitter;
        $client->linkedin = $request->linkedin;
        $client->gst_number = $request->gst_number;
        $client->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('member.clients.index'), __('messages.clientUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return Reply::success(__('messages.clientDeleted'));
    }

    public function data(Request $request)
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'client_details.name', 'client_details.company_name', 'client_details.email', 'users.created_at')
            ->where('roles.name', 'client')
            ->groupBy('users.id');
        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '>=', $request->startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $users = $users->where(DB::raw('DATE(users.`created_at`)'), '<=', $request->endDate);
        }
        if ($request->client != 'all' && $request->client != '') {
            $users = $users->where('users.id', $request->client);
        }

        $users = $users->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '';
                if ($this->user->cans('edit_clients')) {
                    $action .= '<a href="' . route('member.clients.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->cans('view_clients')) {
                    $action .= ' <a href="' . route('member.clients.projects', [$row->id]) . '" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="View Client Details"><i class="fa fa-search" aria-hidden="true"></i></a>';
                }

                if ($this->user->cans('delete_clients')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return $action;
            })
            ->editColumn(
                'name',
                function ($row) {
                    return '<a href="' . route('member.clients.projects', $row->id) . '">' . ucfirst($row->name) . '</a>';
                }
            )
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function showProjects($id)
    {


        $this->client = User::findClient($id);
        return view('member.clients.projects', $this->data);
    }
    
    public function getSubcategory(Request $request)
    {
        $this->subcategories = ClientSubCategory::where('category_id', $request->cat_id)->get();

        return Reply::dataOnly(['subcategory' => $this->subcategories]);
    }

    public function showInvoices($id)
    {
        abort_if(!$this->user->cans('view_invoices'), 403);
        $this->client = User::findClient($id);
        $this->invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->join('users', 'users.id', '=', 'projects.client_id')
            ->select('invoices.invoice_number', 'invoices.total', 'currencies.currency_symbol', 'invoices.issue_date', 'invoices.id')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->get();

        return view('member.clients.invoices', $this->data);
    }

    public function export()
    {
        $rows = User::leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'client_details.company_name',
                'client_details.address',
                'client_details.website',
                'users.created_at'
            )
            ->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Company Name', 'Address', 'Website', 'Created at'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('clients', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Clients');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('clients file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       => true
                    ));
                });
            });
        })->download('xlsx');
    }
    public function seller(SellersDataTableMember $dataTable)
    {

       
        $this->clients = User::allSellerMember( Auth::user()->id);
     
        $this->totalClients = count($this->clients);
        //var_dump($this->totalClients);die;
       $this->categories = ClientCategory::all();
        $this->projects = Project::all();
        $this->contracts = ContractType::all();
       $this->countries = Country::all();
        $this->subcategories = ClientSubCategory::all();
        return $dataTable->render('member.clients.seller', $this->data);
    }

    public function buyer(BuyerDataTableMember $dataTable)
    {
        
        $this->clients = User::allBuyerMember(Auth::user()->id);
     
        $this->totalClients = count($this->clients);
        //var_dump($this->totalClients);die;
       $this->categories = ClientCategory::all();
        $this->projects = Project::all();
        $this->contracts = ContractType::all();
       $this->countries = Country::all();
        $this->subcategories = ClientSubCategory::all();
        return $dataTable->render('member.clients.buyer', $this->data);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sellerShow($id)
    {

        //die('ddd');
        $get_client_details=ClientDetails::where('user_id', '=',$id)->first();
        $companyId=$get_client_details['company_id'];
        $clientId=$get_client_details['id'];
        $leadDetails=DB::table('leads')->where('client_id',$id)->first();
        if(!empty($leadDetails)){
            $this->leadDetails=$leadDetails;
            $againt_id=!empty($leadDetails->agent_id)?$leadDetails->agent_id:Null;
            $source_id=!empty($leadDetails->source_id)?$leadDetails->source_id:Null;
            $status_id=!empty($leadDetails->status_id)?$leadDetails->status_id:Null;
            $stage_id=!empty($leadDetails->stage_id)?$leadDetails->stage_id:Null;
            if(!empty($source_id)){
                $this->sourceDetails=DB::table('lead_sources')->where('id',$source_id)->first();
            }else{
                $this->sourceDetails=array();
            }
            if(!empty($status_id)){
                $this->statusDetails=DB::table('lead_status')->where('id',$status_id)->first();
            }else{
                $this->statusDetails=array();
            }
            if(!empty($stage_id)){
                $this->stageDetails=DB::table('lead_stages')->where('id',$stage_id)->first();
            }else{
                $this->stageDetails=array();
            }
            if(!empty($againt_id)){
                $againtDetails=DB::table('lead_agents')->where('company_id',$againt_id)->first();
                if(!empty($againtDetails)){
                    $userId=$againtDetails->user_id;
                    $this->againtDetails= User::where('id',$userId)->first();
                   // var_dump($this->againtDetails);die;
                }else{
                    $this->againtDetails=array();
                }
            }else{
                $this->leadDetails=array();
                $this->againtDetails=array();
            }
        }else{
            $this->leadDetails=array();
        }
       // echo "<pre>";
        //print_r($leadDetails);
        //die;
        $this->client = User::findClient($id);
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $c_details=$this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        if(is_null($this->clientDetail)){
            abort(404);
        }
        //$this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
      
        return view('member.clients.show', $this->data);
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sellerEdit($id)
    {
      //  die('fff');
        $this->userDetail = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->where('client_details.id', $id)
            ->select('client_details.id', 'client_details.name', 'client_details.email', 'client_details.user_id', 'client_details.mobile', 'users.locale', 'users.status', 'users.login')
            ->first();
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->user_id)->first();
        $leadDetails=DB::table('leads')->where('client_id',$this->userDetail->user_id)->first();
        if(!empty($leadDetails)){
            $this->leadDetails=$leadDetails;
            $againt_id=!empty($leadDetails->agent_id)?$leadDetails->agent_id:Null;
            $source_id=!empty($leadDetails->source_id)?$leadDetails->source_id:Null;
            $status_id=!empty($leadDetails->status_id)?$leadDetails->status_id:Null;
            $stage_id=!empty($leadDetails->stage_id)?$leadDetails->stage_id:Null;
            if(!empty($source_id)){
                $this->sourceDetails=DB::table('lead_sources')->where('id',$source_id)->first();
            }else{
                $this->sourceDetails=array();
            }
            if(!empty($status_id)){
                $this->statusDetails=DB::table('lead_status')->where('id',$status_id)->first();
            }else{
                $this->statusDetails=array();
            }
            if(!empty($stage_id)){
                $this->stageDetails=DB::table('lead_stages')->where('id',$stage_id)->first();
            }else{
                $this->stageDetails=array();
            }
            if(!empty($againt_id)){
                $againtDetails=DB::table('lead_agents')->where('company_id',$againt_id)->first();
                if(!empty($againtDetails)){
                    $userId=$againtDetails->user_id;
                    $this->againtDetails= User::where('id',$userId)->first();
                   // var_dump($this->againtDetails);die;
                }else{
                    $this->againtDetails=array();
                }
            }else{
                $this->leadDetails=array();
                $this->againtDetails=array();
            }
        }else{
            $this->leadDetails=array();
        }
        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        $this->clientWebsite = $this->websiteCheck($this->clientDetail->website);

        $this->countries = Country::all();
        $this->Allcountries = CogCountry::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->stages = LeadStage::all();
        $this->status = LeadStatus::all();
        // die('fff');
        return view('member.clients.edit_seller', $this->data);
    }
      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_document_show($id)
    {
        
        $this->client       = User::findClient($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientDocs   = clientDocs::where('user_id', '=', $this->client->id)->where('type','1')->get();
        $clientController   = new ManageClientsController();
        $this->clientStats  = $clientController->clientStats($id);

        return view('member.clients.docs', $this->data);
    }
    public function quickCreate($id)
    {
        $this->clientID = $id;
        $this->upload = can_upload();
        return view('member.clients.docs-create', $this->data);
    }
     /**
     * @param Request $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function clientDocstore(CreateRequest $request)
    {
       //var_dump($request->type);die;
        $fileFormats = ['image/jpeg', 'image/png', 'image/gif', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/pdf', 'text/plain'];
        foreach ($request->file as $index => $fFormat) {
            if (!in_array($fFormat->getClientMimeType(), $fileFormats)) {
                return Reply::error('This file format not allowed');
            }
        }
        $limitReached = false;
        foreach ($request->name as $index => $name) {
            if (isset($request->file[$index])) {
                $value = $request->file[$index];
                if ($value != '' && $name != '' && $value != null && $name != null) {
                    $upload = can_upload($value->getSize() / (1000 * 1024));
                    if ($upload) {
                        if($request->type !='1'){
                            $res=ClientDocs::where('user_id',$request->user_id)->where('type','2')->delete();
                        }
                        $file = new ClientDocs();
                        $file->user_id = $request->user_id;
                        $file->hashname = Files::uploadLocalOrS3($value, 'client-docs/' . $request->user_id);
                        $file->type =$request->type;
                        $file->name = $name;
                        $file->filename = $value->getClientOriginalName();
                        $file->size = $value->getSize();
                        $file->save();
                    } else {
                        $limitReached = true;
                    }
                }
            }
        }

        if ($limitReached) {
            return Reply::error(__('messages.storageLimitExceed', ['here' => '<a href=' . route('admin.billing.packages') . '>Here</a>']));
        }
        if($request->type!='1'){
         $this->ClientDocs = ClientDocs::where('user_id', $request->user_id)->where('type','2')->get();
        }else{
            $this->ClientDocs = ClientDocs::where('user_id', $request->user_id)->where('type','1')->get();
        }
        
        $view = view('member.clients.docs-list', $this->data)->render();
      
        return Reply::successWithData(__('messages.fileUploaded'), ['html' => $view]);
    }
    public function clientquestion($id){
        $this->client       = User::findClient($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientDocs   = clientDocs::where('user_id', '=', $this->client->id)->where('type',2)->get();
        $clientController   = new ManageClientsController();
        $this->clientStats  = $clientController->clientStats($id);

        return view('member.clients.questiony', $this->data);
    } 

    public function quickCreateQuestion($id)
    {
       // echo $id;die;
        $this->clientID = $id;
        $this->upload = can_upload();
        return view('member.clients.docs-create-question', $this->data);
    }
    public function websiteCheck($email)
    {
        $clientWebsite = $email;

        if (strpos($email, 'http://') !== false)
        {
            $clientWebsite = str_replace('http://', '', $email);
            if(strpos($clientWebsite, 'http://') !== false){
                $clientWebsite = str_replace('http://', '', $clientWebsite);
            }
        }
        if (strpos($email, 'https://') !== false) {
            $clientWebsite = str_replace('https://', '', $email);
            if (strpos($clientWebsite, 'https://') !== false) {
                $clientWebsite = str_replace('https://', '', $clientWebsite);
            }
        }

        return $clientWebsite;
    }
      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clients_update(MemberUpdateClientRequest $request, $id)
    {
        $new_code = Country::select('phonecode')->where('id', $request->phone_code)->first();
        $client = ClientDetails::find($id);

        $client->company_name = $request->company_name;
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->mobile = ($new_code != null) ? $new_code->phonecode.' '.$request->input('mobile') : ' ';
        $client->country_id = $request->input('cog_countries_id');
        $client->address = $request->address;
        $client->office_phone = $request->input('office_phone');
        $client->city = $request->input('city_id');
        $client->state = $request->input('state_id');
        $client->postal_code = $request->input('postal_code');
        $client->category_id = ($request->input('category_id') != 0 && $request->input('category_id') != '') ? $request->input('category_id') : null;
        $client->sub_category_id = ($request->input('sub_category_id') != 0 && $request->input('sub_category_id') != '') ? $request->input('sub_category_id') : null;
        $client->website = $request->hyper_text.''.$request->website;
        $client->note = $request->note;
        $client->skype = $request->skype;
        $client->facebook = $request->facebook;
        $client->twitter = $request->twitter;
        $client->linkedin = $request->linkedin;
        $client->gst_number = $request->gst_number;
        $client->shipping_address = $request->shipping_address;
        $client->email_notifications = $request->email_notifications;
        $client->lead_source = $request->input('lead_source');
        $client->lead_stage = $request->input('stages');
        $client->lead_status = $request->input('status');
       // $client->agent_id = $request->input('agent_id');
        $client->save();
//        $user = User::withoutGlobalScope([[CompanyScope::class], 'active']);
        $user = $client->user;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->country_id = $request->input('phone_code');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->hasFile('image')) {
            $user->image = Files::upload($request->image, 'avatar', 300);
        }

        $user->save();
        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $user = User::withoutGlobalScopes(['active', CompanyScope::class])->findOrFail($client->user_id);

        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->locale = $request->locale;
        $user->save();

        return Reply::redirect(route('member.seller'));
    }
    public function show_buyer($id)
    {
        $get_client_details=ClientDetails::where('user_id', '=',$id)->first();
        $companyId=$get_client_details['company_id'];
        $clientId=$get_client_details['id'];
        $leadDetails=DB::table('leads')->where('client_id',$id)->first();
        if(!empty($leadDetails)){
            $this->leadDetails=$leadDetails;
            $againt_id=!empty($leadDetails->agent_id)?$leadDetails->agent_id:Null;
            $source_id=!empty($leadDetails->source_id)?$leadDetails->source_id:Null;
            $status_id=!empty($leadDetails->status_id)?$leadDetails->status_id:Null;
            $stage_id=!empty($leadDetails->stage_id)?$leadDetails->stage_id:Null;
            if(!empty($source_id)){
                $this->sourceDetails=DB::table('lead_sources')->where('id',$source_id)->first();
            }else{
                $this->sourceDetails=array();
            }
            if(!empty($status_id)){
                $this->statusDetails=DB::table('lead_status')->where('id',$status_id)->first();
            }else{
                $this->statusDetails=array();
            }
            if(!empty($stage_id)){
                $this->stageDetails=DB::table('lead_stages')->where('id',$stage_id)->first();
            }else{
                $this->stageDetails=array();
            }
            if(!empty($againt_id)){
                $againtDetails=DB::table('lead_agents')->where('company_id',$againt_id)->first();
                if(!empty($againtDetails)){
                    $userId=$againtDetails->user_id;
                    $this->againtDetails= User::where('id',$userId)->first();
                   // var_dump($this->againtDetails);die;
                }else{
                    $this->againtDetails=array();
                }
            }else{
                $this->leadDetails=array();
                $this->againtDetails=array();
            }
        }else{
            $this->leadDetails=array();
        }
       // echo "<pre>";
        //print_r($leadDetails);
        //die;
        $this->client = User::findClient($id);
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $c_details=$this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        if(is_null($this->clientDetail)){
            abort(404);
        }
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        //var_dump($this->clientDetail);die;
      
        return view('member.clients.show_buyer', $this->data);
    }
      /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_buyer($id)
    {
        //die('ff');
        //die('In Progress');
        $this->userDetail = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->where('client_details.id', $id)
            ->select('client_details.id', 'client_details.name', 'client_details.email', 'client_details.user_id', 'client_details.mobile', 'users.locale', 'users.status', 'users.login')
            ->first();
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->user_id)->first();
        $leadDetails=DB::table('leads')->where('client_id',$this->userDetail->user_id)->first();
        if(!empty($leadDetails)){
            $this->leadDetails=$leadDetails;
            $againt_id=!empty($leadDetails->agent_id)?$leadDetails->agent_id:Null;
            $source_id=!empty($leadDetails->source_id)?$leadDetails->source_id:Null;
            $status_id=!empty($leadDetails->status_id)?$leadDetails->status_id:Null;
            $stage_id=!empty($leadDetails->stage_id)?$leadDetails->stage_id:Null;
            if(!empty($source_id)){
                $this->sourceDetails=DB::table('lead_sources')->where('id',$source_id)->first();
            }else{
                $this->sourceDetails=array();
            }
            if(!empty($status_id)){
                $this->statusDetails=DB::table('lead_status')->where('id',$status_id)->first();
            }else{
                $this->statusDetails=array();
            }
            if(!empty($stage_id)){
                $this->stageDetails=DB::table('lead_stages')->where('id',$stage_id)->first();
            }else{
                $this->stageDetails=array();
            }
            if(!empty($againt_id)){
                $againtDetails=DB::table('lead_agents')->where('company_id',$againt_id)->first();
                if(!empty($againtDetails)){
                    $userId=$againtDetails->user_id;
                    $this->againtDetails= User::where('id',$userId)->first();
                   // var_dump($this->againtDetails);die;
                }else{
                    $this->againtDetails=array();
                }
            }else{
                $this->leadDetails=array();
                $this->againtDetails=array();
            }
        }else{
            $this->leadDetails=array();
        }
        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        $this->clientWebsite = $this->websiteCheck($this->clientDetail->website);

        $this->countries = Country::all();
        $this->Allcountries = CogCountry::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->stages = LeadStage::all();
        $this->status = LeadStatus::all();
        return view('member.clients.edit_buyer', $this->data);
    }
    public function clientStats($id)
    {
        return DB::table('users')
            ->select(
                DB::raw('(select count(projects.id) from `projects` WHERE projects.client_id = ' . $id . ' and projects.company_id = ' . company()->id . ') as totalProjects'),
                DB::raw('(select count(invoices.id) from `invoices` left join projects on projects.id=invoices.project_id WHERE invoices.status != "paid" and invoices.status != "canceled" and (projects.client_id = ' . $id . ' or invoices.client_id = ' . $id . ') and invoices.company_id = ' . company()->id . ') as totalUnpaidInvoices'),
                DB::raw('(select sum(payments.amount) from `payments` left join projects on projects.id=payments.project_id left join invoices on invoices.id= payments.invoice_id
                WHERE payments.status = "complete" and (projects.client_id = ' . $id . ' or  invoices.client_id = ' . $id. ' )and payments.company_id = ' . company()->id . ') as projectPayments'),


                // DB::raw('(select sum(payments.amount) from `payments` inner join invoices on invoices.id=payments.invoice_id  WHERE payments.status = "complete" and invoices.client_id = ' . $id . ' and payments.company_id = ' . company()->id . ') as invoicePayments'),


                DB::raw('(select count(contracts.id) from `contracts` WHERE contracts.client_id = ' . $id . ' and contracts.company_id = ' . company()->id . ') as totalContracts')
            )
            ->first();
    }
    public function buyer_doucument($id)
    {
      //  echo 'sss';die;
        
        $this->client       = User::findClient($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientDocs   = clientDocs::where('user_id', '=', $this->client->id)->get();
        $clientController   = new ManageClientsController();
        $this->clientStats  = $clientController->clientStats($id);
       
        return view('member.clients.docs_buyer', $this->data);
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_buyer(MemberUpdateClientRequest $request, $id)
    {
      //  echo 'ddd';die;
        $new_code = Country::select('phonecode')->where('id', $request->phone_code)->first();
        $client = ClientDetails::find($id);

        $client->company_name = $request->company_name;
        $client->name = $request->input('name');
     //   $client->email = $request->input('email');
        $client->mobile = ($new_code != null) ? $new_code->phonecode.' '.$request->input('mobile') : ' ';
        $client->country_id = $request->input('cog_countries_id');
        $client->address = $request->address;
        $client->office_phone = $request->input('office_phone');
        $client->city = $request->input('city_id');
        $client->state = $request->input('state_id');
        $client->postal_code = $request->input('postal_code');
        $client->category_id = ($request->input('category_id') != 0 && $request->input('category_id') != '') ? $request->input('category_id') : null;
        $client->sub_category_id = ($request->input('sub_category_id') != 0 && $request->input('sub_category_id') != '') ? $request->input('sub_category_id') : null;
        $client->website = $request->hyper_text.''.$request->website;
        $client->note = $request->note;
        $client->skype = $request->skype;
        $client->facebook = $request->facebook;
        $client->twitter = $request->twitter;
        $client->linkedin = $request->linkedin;
        $client->gst_number = $request->gst_number;
        $client->shipping_address = $request->shipping_address;
        $client->email_notifications = $request->email_notifications;
        $client->lead_source = $request->input('lead_source');
        $client->lead_stage = $request->input('stages');
        $client->lead_status = $request->input('status');
        $client->agent_id = $request->input('agent_id');
        $client->save();
//        $user = User::withoutGlobalScope([[CompanyScope::class], 'active']);
        $user = $client->user;
        $user->name = $request->input('name');
      //  $user->email = $request->input('email');
        $user->country_id = $request->input('phone_code');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->hasFile('image')) {
            $user->image = Files::upload($request->image, 'avatar', 300);
        }

        $user->save();
        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $user = User::withoutGlobalScopes(['active', CompanyScope::class])->findOrFail($client->user_id);

        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->locale = $request->locale;
        $user->save();

        return Reply::redirect(route('member.buyer'));
    }
}
