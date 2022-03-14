<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LeadsDataTable;
use App\GoogleAccount;
use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\FollowUp\UpdateFollowUpRequest;
use App\Http\Requests\Gdpr\SaveConsentLeadDataRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Lead;
use App\Country;
use App\CogCountry;
use App\LeadAgent;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use App\LeadType;
use App\LeadStage;
use App\CogState;
use App\CogCity;
use App\LeadCategory;
use App\ClientCategory;
use App\ClientSubCategory;
use App\Currency;
use App\LeadAssignHistory;
use App\PurposeConsent;
use App\PurposeConsentLead;
use App\Services\Google;
use App\TaskUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-people');
        $this->pageTitle = 'app.lead';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('leads', $this->user->modules), 403);
            return $next($request);
        });
    }

    public function index(LeadsDataTable $dataTable)
    {
        $this->totalLeads = Lead::all();
        $this->sources = LeadSource::all();
        $this->categories = LeadCategory::all();
        $this->stages = LeadStage::all();
        $this->currencies = Currency::all();
        $this->types = LeadType::all();
        $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
            return $value->client_id != null;
        });
        $this->totalLeads = Lead::all()->count();
        $this->totalClientConverted = $this->totalClientConverted->count();

        $this->pendingLeadFollowUps = LeadFollowUp::where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes')
            ->where('leads.company_id', company()->id)
            ->get();

        $this->pendingLeadFollowUps = $this->pendingLeadFollowUps->count();
        $this->leadAgents = LeadAgent::with('user')->has('user')->get();
        return $dataTable->render('admin.lead.index', $this->data);
    }

    public function show($id)
    {
        $this->lead = Lead::findOrFail($id)->withCustomFields();
        $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        $this->categories = LeadCategory::all();
        $this->types = LeadType::all();
        $this->stages = LeadStage::all();
        $this->currencies = Currency::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        return view('admin.lead.show', $this->data);
    }

    /*
     *
     */
    public function create()
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        $this->categories = LeadCategory::all();
        $this->types = LeadType::all();
        $this->stages = LeadStage::all();
        $this->countries = Country::all();
        $this->Allcountries = CogCountry::all();
        $this->currencies = Currency::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $lead = new Lead();
        $this->currency_id = company()->currency_id;
        $this->fields = $lead->getCustomFieldGroupsWithFields()->fields;

        return view('admin.lead.create', $this->data);
    }

    /*
     *
     */
    public function store(StoreRequest $request)
    {
        $leadStatus = LeadStatus::where('default', '1')->first();

        $lead = new Lead();
        $uniqid = Str::random(9);
        $lead->lead_id = uniqid();
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_name = $request->salutation.' '.$request->name;
        $lead->client_email = $request->email;
        $lead->mobile = $request->input('phone_code').' '.$request->input('mobile');
        $lead->office_phone = $request->office_phone;
        $lead->cog_countries_id = $request->cog_countries_id;
        $lead->cog_state_id = $request->state_id;
        $lead->cog_city_id = $request->city_id;
        $lead->postal_code = $request->postal_code;
        $lead->note = $request->note;
        $lead->category_id = $request->category_id;
        $lead->client_type = $request->type_id;
        $lead->stage_id = $request->stage_id;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->source_id = $request->source_id;
        $lead->created_by_id = $this->user->id;
        $lead->value = ($request->value) ? $request->value : 0;
        $lead->currency_id = ($request->currency_id) ? $request->currency_id : company()->currency_id;
        $lead->industry_id = ($request->input('industry_id') != 0 && $request->input('industry_id') != '') ? $request->input('industry_id') : null;
        $lead->sub_industry_id = ($request->input('sub_industry_id') != 0 && $request->input('sub_industry_id') != '') ? $request->input('sub_industry_id') : null;
        $lead->status_id = $leadStatus->id;
        // echo "<pre>"; print_r($lead);exit;
        $lead->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $lead->updateCustomFieldData($request->get('custom_fields_data'));
        }
        
        

        // Log search
        $this->LogEntry($lead);
        
        
        //maintain assign log table created by Basu
        /*$AssignHistory = new LeadAssignHistory();
        $AssignHistory->lead_id = $lead->id;
        $AssignHistory->client_id =  null;
        $AssignHistory->assigned_agent = ($request->agent_id) ? $request->agent_id : null;
        $AssignHistory->assigned_by = $this->user->id;
        // echo "<pre>"; print_r($AssignHistory);exit;
        $AssignHistory->save();*/
        
        $post1['lead_id'] = $lead->id;
        $post1['client_id']  = null;
        $post1['assigned_agent']  = ($request->agent_id) ? $request->agent_id : null;
        $post1['company_id'] = $this->user->company_id;
        $post1['assigned_by'] = $this->user->id;
        $insert=DB::table('lead_assign_history')->insert($post1);

        return Reply::redirect(route('admin.leads.index'), __('messages.LeadAddedUpdated'));
    }

    private function LogEntry($lead)
    {
        $this->logSearchEntry($lead->id, $lead->client_name, 'admin.leads.show', 'lead');
        $this->logSearchEntry($lead->id, $lead->client_email, 'admin.leads.show', 'lead');

        if (!is_null($lead->company_name)) {
            $this->logSearchEntry($lead->id, $lead->company_name, 'admin.leads.show', 'lead');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->lead = Lead::findOrFail($id)->withCustomFields();
        $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        $this->categories = LeadCategory::all();
        $this->stages = LeadStage::all();
        $this->types = LeadType::all();
        $this->countries = Country::all();
        $this->Allcountries = CogCountry::all();
        $this->AllStates = CogState::all();
        $this->AllCities = CogCity::all();
        $this->currencies = Currency::all();
        $this->currency_id = company()->currency_id;
        $this->idustries = ClientCategory::all();
        $this->subidustries = ClientSubCategory::all();
        return view('admin.lead.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array|string[]
     */
    public function update(UpdateRequest $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_name = $request->client_name;
        $lead->client_email = $request->email;
        $lead->mobile = $request->input('phone_code').' '.$request->input('mobile');
        $lead->office_phone = $request->office_phone;
        $lead->cog_city_id = $request->cog_city_id;
        $lead->cog_state_id = $request->cog_state_id;
        $lead->cog_countries_id = $request->cog_countries_id;
        $lead->postal_code = $request->postal_code;

        $lead->note = $request->note;
        $lead->status_id = $request->status;
        $lead->source_id = $request->source;
        $lead->category_id = $request->category_id;
        $lead->client_type = $request->type_id;
        $lead->stage_id = $request->stage_id;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->value = ($request->value) ? $request->value : 0;
        $lead->currency_id = ($request->currency_id) ? $request->currency_id : company()->currency_id;
        $lead->industry_id = ($request->input('industry_id') != 0 && $request->input('industry_id') != '') ? $request->input('industry_id') : null;
        $lead->sub_industry_id = ($request->input('sub_industry_id') != 0 && $request->input('sub_industry_id') != '') ? $request->input('sub_industry_id') : null;
        $lead->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $lead->updateCustomFieldData($request->get('custom_fields_data'));
        }
        
        //checking assigned_agent and assigned_by 
        // echo "Basu";die;
        $agent_id = ($request->agent_id) ? $request->agent_id : null;
        $data = DB::table('lead_assign_history')->where(['lead_id'=> $id,'assigned_agent'=> $agent_id,'assigned_by'=> $this->user->id])->get();
        $dataCount = $data->count();
        if($dataCount==0){
            $post1['lead_id'] = $lead->id;
            $post1['company_id'] = $this->user->company_id;
            $post1['client_id']  = null;
            $post1['assigned_agent']  = $agent_id;
            $post1['assigned_by'] = $this->user->id;
            $insert=DB::table('lead_assign_history')->insert($post1);
        }

        return Reply::redirect(route('admin.leads.index'), __('messages.LeadUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lead::destroy($id);
        $this->totalLeads = Lead::all();
        $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
            return $value->client_id != null;
        });
        $this->totalLeadsCount = $this->totalLeads->count();
        $this->totalClientConverted = $this->totalClientConverted->count();

        $this->pendingLeadFollowUps = LeadFollowUp::where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes')
            ->where('leads.company_id', company()->id)
            ->get()->count();;
        $leadData = [
            'totalLeadsCount' => $this->totalLeadsCount,
            'totalClientConverted' => $this->totalClientConverted,
            'pendingLeadFollowUps' => $this->pendingLeadFollowUps,
        ];

        return Reply::successWithData(__('messages.LeadDeleted'), ['data' => $leadData]);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteFollow($id)
    {
        LeadFollowUp::destroy($id);
        return Reply::success(__('messages.followUp.deletedSuccess'));
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        $lead = Lead::findOrFail($request->leadID);
        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    public function gdpr($leadID)
    {
        $this->lead = Lead::findOrFail($leadID);
        $this->allConsents = PurposeConsent::with(['lead' => function ($query) use ($leadID) {
            $query->where('lead_id', $leadID)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('admin.lead.gdpr.show', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentLead::select('purpose_consent.name', 'purpose_consent_leads.created_at', 'purpose_consent_leads.status', 'purpose_consent_leads.ip', 'users.name as username', 'purpose_consent_leads.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_leads.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_leads.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_leads.lead_id', $id);

        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {
                if ($row->status == 'agree') {
                    $status = __('modules.gdpr.optIn');
                } else if ($row->status == 'disagree') {
                    $status = __('modules.gdpr.optOut');
                } else {
                    $status = '';
                }

                return $status;
            })
            ->make(true);
    }

    public function saveConsentLeadData(SaveConsentLeadDataRequest $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentLead();
        $newConsentLead->lead_id = $lead->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.leads.gdpr', $lead->id);

        return Reply::redirect($url);
    }

    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpCreate($leadID)
    {
        $this->leadID = $leadID;
        return view('admin.lead.follow_up', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function followUpStore(\App\Http\Requests\FollowUp\StoreRequest $request)
    {

        $followUp = new LeadFollowUp();
        $followUp->lead_id = $request->lead_id;

        if($request->has('type')){
            $followUp->next_follow_up_date = Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
        }
        else{
            $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
        }

        $followUp->remark = $request->remark;
        $followUp->save();

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpAddedSuccess'), ['html' => $view]);
    }

    public function followUpShow($leadID)
    {
        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);
        return view('admin.lead.followup.show', $this->data);
    }

    public function editFollow($id)
    {
        $this->follow = LeadFollowUp::findOrFail($id);
        $view = view('admin.lead.followup.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    public function UpdateFollow(UpdateFollowUpRequest $request)
    {
        $followUp = LeadFollowUp::findOrFail($request->id);
        $followUp->lead_id = $request->lead_id;

        if($request->has('type')){
            $followUp->next_follow_up_date = Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
        }
        else{
            $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
        }

        $followUp->remark = $request->remark;
        $followUp->save();

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpUpdatedSuccess'), ['html' => $view]);
    }

    public function followUpSort(CommonRequest $request)
    {
        $leadId = $request->leadId;
        $this->sortBy = $request->sortBy;

        $this->lead = Lead::findOrFail($leadId);
        if ($request->sortBy == 'next_follow_up_date') {
            $order = 'asc';
        } else {
            $order = 'desc';
        }

        $follow = LeadFollowUp::where('lead_id', $leadId)->orderBy($request->sortBy, $order);


        $this->lead->follow = $follow->get();

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.followUpFilter'), ['html' => $view]);
    }

    public function kanbanboard(Request $request)
    {
        $this->startDate = $startDate = Carbon::now()->subDays(15)->format($this->global->date_format);
        $this->endDate = $endDate = Carbon::now()->addDays(15)->format($this->global->date_format);
        $this->leadAgents = LeadAgent::with('user')->get();
        if (request()->ajax()) {

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

            $boardColumns = LeadStatus::with(['leads' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['lead_agent', 'lead_agent.user'])
                    ->select('leads.*', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
                    ->groupBy('leads.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(leads.`created_at`)'), [$startDate, $endDate]);

                    $task->orWhereBetween(DB::raw('DATE(leads.`created_at`)'), [$startDate, $endDate]);
                });


                if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all') {
                    $q->where('leads.agent_id', '=', $request->assignedTo);
                }
            }])->orderBy('priority', 'asc')->get();

            $this->boardColumns = $boardColumns;

            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->assignedTo = $request->assignedTo;

            $view = view('admin.lead.board_data', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('admin.lead.kanban_board', $this->data);
    }

    public function updateIndex(Request $request)
    {

        $taskIds = $request->taskIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        $board = LeadStatus::findOrFail($boardColumnIds[0]);
        $valueData = [];
        if (isset($taskIds) && count($taskIds) > 0) {

            $taskIds = (array_filter($taskIds, function ($value) {
                return $value !== null;
            }));

            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {
                    $task = Lead::findOrFail($taskId);
                    $task->update(
                        [
                            'status_id' => $boardColumnIds[$key],
                            'column_priority' => $priorities[$key]
                        ]
                    );
                }
            }

            if ($request->draggingTaskId == 0 && $request->draggedTaskId != 0) {
                // $this->logTaskActivity($request->draggedTaskId, $this->user->id, "statusActivity", $board->id);
                // $updatedTask = Task::findOrFail($request->draggedTaskId);
                // event(new TaskUpdated($updatedTask));
            }
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $boardColumns = LeadStatus::with(['leads' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['lead_agent', 'lead_agent.user', 'currency'])
                    ->select('leads.*', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
                    ->groupBy('leads.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(leads.`created_at`)'), ["$startDate", "$endDate"]);

                    $task->orWhereBetween(DB::raw('DATE(leads.`created_at`)'), ["$startDate", "$endDate"]);
                });

                if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all') {
                    $q->where('leads.agent_id', '=', $request->assignedTo);
                }

            }])->orderBy('priority', 'asc')->get();

            foreach($boardColumns as $columnData){
                $valData = ($columnData->leads) ? $columnData->leads->sum('value') : 0;
                $valueData[] = ['columnId' => $columnData->id, 'value' => $valData];
            }
        }



        return Reply::dataOnly(['status' => 'success', 'columnData' => $valueData]);
    }
    
    public function getState(Request $request)
    {
        $this->states = CogState::where('cog_countries_id', $request->country_id)->get();

        return Reply::dataOnly(['AllStates' => $this->states]);
    }
    public function getCity(Request $request)
    {
        $this->cities = CogCity::where('cog_states_id', $request->state_id)->get();

        return Reply::dataOnly(['AllCities' => $this->cities]);
    }

}
