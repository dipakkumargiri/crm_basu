<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Member\MemberDiscussionDataTable;
use App\DataTables\Admin\ProjectsDataTable;
use App\Discussion;
use App\DiscussionCategory;
use App\DiscussionReply;
use App\Helper\Reply;
use App\Http\Requests\Project\StoreProject;
use App\Http\Requests\Project\UpdateProject;
use App\Pinned;
use App\Project;
use App\Payment;
use App\ProjectActivity;
use App\ProjectCategory;
use App\ProjectFile;
use App\ProjectMember;
use App\ProjectTemplate;
use App\ProjectTemplateMember;
use App\ProjectTimeLog;
use App\SubTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskUser;

use App\ClientCategory;
use App\ClientSubCategory;
use App\Lead;
use App\LeadAgent;
use App\LeadStage;
use App\LeadSource;
use App\CogCountry;
use App\CogState;
use App\Currency;
use App\CogCity;

use App\ProjectAggrement;
use App\ProjectBusiness;
use App\ProjectDetail;
use App\ProjectGeneralAsset;
use App\ProjectFinance;
use App\ProjectMarketingStatus;

use App\Traits\ProjectProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class MemberProjectsController
 * @package App\Http\Controllers\Member
 */
class MemberProjectsController extends MemberBaseController
{
    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';

        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projects', $this->user->modules), 403);
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ProjectsDataTable $dataTable)
    {
        $this->clients = User::allClients();
        $this->clients = User::allSeller();
        $this->totalProjects = Project::count();
        $this->finishedProjects = Project::finished()->count();
        $this->inProcessProjects = Project::inProcess()->count();
        $this->onHoldProjects = Project::onHold()->count();
        $this->canceledProjects = Project::canceled()->count();
        $this->notStartedProjects = Project::notStarted()->count();
        $this->overdueProjects = Project::overdue()->count();
        $this->allEmployees = User::allEmployees();
        $this->projects = Project::all();
        $this->projectBudgetTotal = Project::sum('project_budget');
        $this->categories = ProjectCategory::all();
        $this->projectEarningTotal = Payment::join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 'complete')
            ->whereNotNull('projects.project_budget')
            ->whereNotNull('payments.project_id')
            ->sum('payments.amount'); 
        

       

        //return view('member.projects.index', $this->data);
        return $dataTable->render('member.projects.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
   /* public function edit($id)
    {
        $this->project = Project::findOrFail($id)->withCustomFields();

        if (!$this->project->isProjectAdmin && !$this->user->cans('edit_projects')) {
            abort(403);
        }

        $this->clients = User::allClients();
        $this->categories = ProjectCategory::all();
        $this->fields = $this->project->getCustomFieldGroupsWithFields()->fields;

        return view('member.projects.edit', $this->data);
    }*/

    public function edit($id)
    {
        echo "test";
        exit;
        /*$this->clients = User::allSeller();
        $this->categories = ProjectCategory::all();
        $this->project = Project::with('aggrement', 'business', 'detail', 'asset', 'finance', 'lead')->findOrFail($id)->withCustomFields();
        // echo "<pre>"; print_r($this->project);exit;
        $this->fields = $this->project->getCustomFieldGroupsWithFields()->fields;
        $this->currencies = Currency::all();
        
        $this->marketing_status = ProjectMarketingStatus::all();
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->stages = LeadStage::all();
        $this->sources = LeadSource::all();
        $this->Allcountries = CogCountry::all();
        $this->AllStates = CogState::all();
        $this->AllCities = CogCity::all();
        $this->employees = User::allEmployees()->where('status', 'active');
        
        return view('admin.projects.edit', $this->data);*/
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->userDetail = auth()->user();

        $this->project = Project::findOrFail($id)->withCustomFields();
        $this->fields = $this->project->getCustomFieldGroupsWithFields()->fields;

        $isMember = ProjectMember::checkIsMember($id, $this->user->id);

        // Check authorised user
        if ($this->project->isProjectAdmin || $this->user->cans('view_projects') || $isMember) {
            $this->activeTimers = ProjectTimeLog::projectActiveTimers($this->project->id);

            $this->openTasks = Task::projectOpenTasks($this->project->id, $this->userDetail->id);
            $this->openTasksPercent = (count($this->openTasks) == 0 ? '0' : (count($this->openTasks) / count($this->project->tasks)) * 100);

            // TODO::ProjectDeadline to do
            $this->daysLeft = 0;
            $this->daysLeftFromStartDate = 0;
            $this->daysLeftPercent = 0;

            if ($this->project->deadline) {
                $this->daysLeft = $this->project->deadline->diff(Carbon::now())->format('%d') + ($this->project->deadline->diff(Carbon::now())->format('%m') * 30) + ($this->project->deadline->diff(Carbon::now())->format('%y') * 12);
                $this->daysLeftFromStartDate = $this->project->deadline->diff($this->project->start_date)->format('%d') + ($this->project->deadline->diff($this->project->start_date)->format('%m') * 30) + ($this->project->deadline->diff($this->project->start_date)->format('%y') * 12);
                $this->daysLeftPercent = ($this->daysLeftFromStartDate == 0 ? '0' : (($this->daysLeft / $this->daysLeftFromStartDate) * 100));
            }

            $this->hoursLogged = ProjectTimeLog::projectTotalMinuts($this->project->id);
            $minute = 0;
            $hour = intdiv($this->hoursLogged, 60);

            if (($this->hoursLogged % 60) > 0) {
                $minute = ($this->hoursLogged % 60);
                $this->hoursLogged = $hour . ':' . $minute;
            } else {
                $this->hoursLogged = $hour;
            }

            $this->recentFiles = ProjectFile::where('project_id', $this->project->id)->orderBy('id', 'desc')->limit(10)->get();
            $this->activities = ProjectActivity::getProjectActivities($id, 10, $this->userDetail->id);

            return view('member.projects.show', $this->data);
        } else {
            // If not authorised user
            abort(403);
        }
    }

    public function data(Request $request)
    {
        $this->userDetail = auth()->user();
        $projects = Project::selectRaw('projects.id, projects.project_name, projects.project_admin, projects.project_summary, projects.start_date, projects.deadline,
         projects.notes, projects.category_id, projects.client_id, projects.feedback, projects.completion_percent, projects.created_at, projects.updated_at,
          projects.status,
           ( select count("id") from pinned where pinned.project_id = projects.id and pinned.user_id = ' . user()->id . ') as pinned_project');
        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->userDetail->id);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'incomplete') {
                $projects->where('completion_percent', '<', '100');
            } elseif ($request->status == 'complete') {
                $projects->where('completion_percent', '=', '100');
            } else {
                $projects->where('status', '=', $request->status);
            }
        }


        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $projects->where('client_id', $request->client_id);
        }

        $projects->groupBy('projects.id')->get();

        return DataTables::of($projects)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">' . trans('app.action') . ' <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">';

                if ($row->project_admin == $this->userDetail->id || $this->user->cans('edit_projects')) {
                    $action .= '<li><a href="' . route('member.projects.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>';
                }
                $action .= '<li><a href="' . route('member.projects.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . trans('app.view') . '</a></li>';
                /*$action .= '<li><a href="' . route('member.projects.gantt', [$row->id]) . '"><i class="fa fa-bar-chart" aria-hidden="true"></i> ' . trans('modules.projects.viewGanttChart') . '</a></li>';
                $action .= '<li><a href="' . route('front.gantt', [md5($row->id)]) . '" target="_blank"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . trans('modules.projects.viewPublicGanttChart') . '</a></li>';*/

                if ($this->user->cans('delete_projects')) {
                    $action .= '<li><a href="javascript:;" data-user-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';
                }

                $action .= '</ul> </div>';

                return $action;
            })
            ->addColumn('members', function ($row) {
                $members = '';

                if (count($row->members) > 0) {
                    foreach ($row->members as $member) {
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '"
                        alt="user" class="img-circle" width="25" height="25"> '. ucwords($member->user->name);
                    }
                } else {
                    $members .= __('messages.noMemberAddedToProject');
                }
                /*if ($this->user->cans('add_projects')) {
                    $members .= '<a class="btn btn-primary btn-circle" style="width: 25px;height: 25px;padding: 3px;" data-toggle="tooltip" data-original-title="' . __('modules.projects.addMemberTitle') . '"  href="' . route('member.project-members.show', $row->id) . '"><i class="fa fa-plus" ></i></a>';
                }*/
                return $members;
            })

            ->editColumn('project_name', function ($row) {
                $pin = '';
                if (($row->pinned_project)) {
                    $pin = '<br><span class="font-12"  data-toggle="tooltip" data-original-title="' . __('app.pinned') . '"><i class="icon-pin icon-2"></i></span>';
                }
                $name = ' <a href="' . route('member.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a> ' . $pin;

                return $name;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format($this->global->date_format);
                }

                return '-';
            })
            ->editColumn('client_id', function ($row) {
                if (!is_null($row->client_id)) {
                    return ucwords($row->client->name);
                } else {
                    // return '--';
                    return (!is_null($row->clientdetails) && $row->clientdetails->company_name != '') ? ucwords($row->client ? $row->client->name : '') . '<br>[' . $row->clientdetails->company_name . ']' : ucwords($row->client ? $row->client->name : '');
                }
            })
            ->editColumn('completion_percent', function ($row) {
                if ($row->completion_percent < 50) {
                    $statusColor = 'danger';
                    $status = __('app.progress');
                } elseif ($row->completion_percent >= 50 && $row->completion_percent < 75) {
                    $statusColor = 'warning';
                    $status = __('app.progress');
                } else {
                    $statusColor = 'success';
                    $status = __('app.progress');

                    if ($row->completion_percent >= 100) {
                        $status = __('app.completed');
                    }
                }

                return '<h5>' . $status . '<span class="pull-right">' . $row->completion_percent . '%</span></h5><div class="progress">
                  <div class="progress-bar progress-bar-' . $statusColor . '" aria-valuenow="' . $row->completion_percent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $row->completion_percent . '%" role="progressbar"> <span class="sr-only">' . $row->completion_percent . '% Complete</span> </div>
                </div>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'in progress') {
                    $status = '<label class="label label-info">' . __('app.inProgress') . '</label>';
                } else if ($row->status == 'on hold') {
                    $status = '<label class="label label-warning">' . __('app.onHold') . '</label>';
                } else if ($row->status == 'not started') {
                    $status = '<label class="label label-warning">' . __('app.notStarted') . '</label>';
                } else if ($row->status == 'canceled') {
                    $status = '<label class="label label-danger">' . __('app.canceled') . '</label>';
                } else if ($row->status == 'finished') {
                    $status = '<label class="label label-success">' . __('app.finished') . '</label>';
                } else if ($row->status == 'under review') {
                    $status = '<label class="label label-warning">' . __('app.underReview') . '</label>';
                }
                return $status;
            })
            ->rawColumns(['project_name', 'action', 'members', 'completion_percent', 'status'])
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback')
            ->removeColumn('start_date')
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProject $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }
        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');

        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
        } else {
            $project->deadline = null;
        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = ($request->client_id == 'null' || $request->client_id == '') ? null : $request->client_id;
        $project->feedback = $request->feedback;

        if ($request->calculate_task_progress) {
            $project->calculate_task_progress = $request->calculate_task_progress;
            $project->completion_percent = $this->calculateProjectProgress($id);
        } else {
            $project->calculate_task_progress = 'false';
            $project->completion_percent = $request->completion_percent;
        }

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = 'disable';
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = 'disable';
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = 'disable';
        }
        $project->status = $request->status;

        $project->save();

        $this->logProjectActivity($project->id, ucwords($project->project_name) . __('modules.projects.projectUpdated'));
        return Reply::redirect(route('member.projects.edit', $id), __('messages.projectUpdated'));
    }

    public function create()
    {
        abort_if(!$this->user->cans('add_projects'), 403);

        $this->clients = User::allSeller();
        $this->categories = ProjectCategory::all();
        $this->templates = ProjectTemplate::all();
        $this->currencies = Currency::all();
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->stages = LeadStage::all();
        $this->sources = LeadSource::all();
        $this->Allcountries = CogCountry::all();
        $this->marketing_status = ProjectMarketingStatus::all();
        $this->employees = User::allEmployees()->where('status', '=', 'active');
        
        $project = new Project();
        $this->fields = $project->getCustomFieldGroupsWithFields()->fields;
        $this->upload = can_upload();
        return view('member.projects.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /*
     public function store(StoreProject $request)
    {
        $project = new Project();
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }

        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');

        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = $request->client_id;

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = 'disable';
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = 'disable';
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = 'disable';
        }

        $project->status = $request->status;

        $project->save();

        if ($request->template_id) {
            $template = ProjectTemplate::findOrFail($request->template_id);
            foreach ($template->members as $member) {
                $projectMember = new ProjectMember();

                $projectMember->user_id    = $member->user_id;
                $projectMember->project_id = $project->id;
                $projectMember->save();
            }
            foreach ($template->tasks as $task) {
                $projectTask = new Task();

                $projectTask->project_id  = $project->id;
                $projectTask->heading     = $task->heading;
                $projectTask->description = $task->description;
                $projectTask->due_date    = Carbon::now()->addDay()->format('Y-m-d');
                $projectTask->status      = 'incomplete';
                $projectTask->save();

                foreach ($task->users_many as $key => $value) {
                    TaskUser::create(
                        [
                            'user_id' => $value->id,
                            'task_id' => $projectTask->id
                        ]
                    );
                }

                foreach ($task->subtasks as $key => $value) {
                    SubTask::create(
                        [
                            'title' => $value->title,
                            'task_id' => $projectTask->id
                        ]
                    );
                }
            }
        }

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $project->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $users = $request->user_id;

        foreach ($users as $user) {
            $member = new ProjectMember();
            $member->user_id = $user;
            $member->project_id = $project->id;
            $member->save();

            $this->logProjectActivity($project->id, ucwords($member->user->name) . ' ' . __('messages.isAddedAsProjectMember'));
        }

        $this->logSearchEntry($project->id, 'Project: ' . $project->project_name, 'admin.projects.show', 'project');

        $this->logProjectActivity($project->id, ucwords($project->project_name) . ' ' . __('messages.addedAsNewProject'));

        return Reply::dataOnly(['projectID' => $project->id]);

        //        return Reply::redirect(route('member.projects.index'), __('modules.projects.projectUpdated'));
    }*/



    public function store(StoreProject $request)
    {
        $memberExistsInTemplate = false;

        $project = new Project();
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }
        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');

        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = $request->client_id;

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = 'disable';
        }
        if (($request->client_view_task) && ($request->read_only)) {
            $project->read_only = 'enable';
        } else {
            $project->read_only = 'disable';
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = 'disable';
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = 'disable';
        }

        $project->project_budget = $request->project_budget;

        $project->currency_id = $request->currency_id;
        if (!$request->currency_id) {
            $project->currency_id = $this->global->currency_id;
        }

        $project->hours_allocated = $request->hours_allocated;
        $project->lead_id = $request->lead_id;
        $project->agent_id = $request->agent_id;
        $project->franchise = $request->franchise;
        $project->franchise_office = $request->franchise_office;
        $project->marketing_status_id = $request->marketing_status_id;
        $project->status = $request->status;
        $project->has_image = $request->has_image;
        $project->project_admin = $this->user->id;
        $project->save();

    
            
                $agent = LeadAgent::where('id', $request->agent_id)->first();
                
                $user_id = ($agent) ? $agent->user_id : '';
                
                $projectMember = new ProjectMember();
                $project_id = $project->id;
                // $project_id = 1;
                $projectMember->user_id    = $user_id;
                $projectMember->project_id = $project_id;
                $projectMember->save();
                
                $member = ProjectMember::firstOrCreate([
                    'user_id' => $user_id,
                    'project_id' => $project_id
                ]);
    
                $this->logProjectActivity($project_id, ucwords($member->user->name) . ' ' . __('messages.isAddedAsProjectMember'));
               
        $ProjectAggrement = new ProjectAggrement();
        $ProjectAggrement->project_id = $project_id;
        $ProjectAggrement->reffered_by = $request->reffered_by;
        $ProjectAggrement->reffered_fee = $request->reffered_fee;
        $ProjectAggrement->agreement_type = $request->agreement_type;
        $ProjectAggrement->agency_type = $request->agency_type;
        $ProjectAggrement->save();
        
        
        $ProjectBusiness = new ProjectBusiness();
        $ProjectBusiness->project_id = $project_id;
        $ProjectBusiness->represented = $request->represented;
        $ProjectBusiness->established = $request->established;
        $ProjectBusiness->owned = $request->owned;
        $ProjectBusiness->from_ownership = $request->from_ownership;
        $ProjectBusiness->comments = $request->comments;
        $ProjectBusiness->down_payment = $request->down_payment;
        $ProjectBusiness->vat_value = $request->vat_value;
        $ProjectBusiness->value_tax = $request->value_tax;
        $ProjectBusiness->realestate_price = $request->realestate_price;
        $ProjectBusiness->training_support = $request->training_support;
        $ProjectBusiness->nooftraining_week = $request->nooftraining_week;
        $ProjectBusiness->sale_reason = $request->sale_reason;
        $ProjectBusiness->save();
        
        
        $ProjectDetail = new ProjectDetail();
        $ProjectDetail->project_id = $project_id;
        $ProjectDetail->ad_headline = $request->ad_headline;
        $ProjectDetail->listing_url = $request->listing_url;
        $ProjectDetail->office_location = $request->office_location;
        $ProjectDetail->promoted = $request->promoted;
        $ProjectDetail->agent_promoted = $request->agent_promoted;
        $ProjectDetail->project_summary = $request->project_summary;
        $ProjectDetail->business_history = $request->business_history;
        $ProjectDetail->competitive_overview = $request->competitive_overview;
        $ProjectDetail->potential_growth = $request->potential_growth;
        $ProjectDetail->showing_instruction = $request->showing_instruction;
        $ProjectDetail->showing_comments = $request->showing_comments;
        $ProjectDetail->general_location = $request->general_location;
        $ProjectDetail->gmap_url = $request->gmap_url;
        $ProjectDetail->address = $request->address;
        $ProjectDetail->post_code = $request->post_code;
        $ProjectDetail->country_id = $request->country_id;
        $ProjectDetail->state_id = $request->state_id;
        $ProjectDetail->city_id  = $request->city_id;
        $ProjectDetail->save();
        
        $ProjectGeneralAsset = new ProjectGeneralAsset();
        $ProjectGeneralAsset->project_id = $project_id;
        $ProjectGeneralAsset->business_hours  = $request->business_hours;
        $ProjectGeneralAsset->weekly_hours  = $request->weekly_hours;
        $ProjectGeneralAsset->relocation  = $request->relocation;
        $ProjectGeneralAsset->franchisee_operations  = $request->franchisee_operations;
        $ProjectGeneralAsset->franchise_mart  = $request->franchise_mart;
        $ProjectGeneralAsset->home_based  = $request->home_based;
        $ProjectGeneralAsset->no_of_emp  = $request->no_of_emp;
        $ProjectGeneralAsset->inventory_value  = $request->inventory_value;
        $ProjectGeneralAsset->ff_value  = $request->ff_value;
        $ProjectGeneralAsset->accounts_recieveable  = $request->accounts_recieveable;
        $ProjectGeneralAsset->leashold  = $request->leashold;
        $ProjectGeneralAsset->estate_value  = $request->estate_value;
        $ProjectGeneralAsset->other_assets  = $request->other_assets;
        $ProjectGeneralAsset->total_assets  = $request->total_assets;
        $ProjectGeneralAsset->inventory_inlcuded  = $request->inventory_inlcuded;
        $ProjectGeneralAsset->fe_inlcuded  = $request->fe_inlcuded;
        $ProjectGeneralAsset->accounts_recieveable_include  = $request->accounts_recieveable_include;
        $ProjectGeneralAsset->leashold_include  = $request->leashold_include;
        $ProjectGeneralAsset->estate_value_available  = $request->estate_value_available;
        $ProjectGeneralAsset->estate_value_include  = $request->estate_value_include;
        $ProjectGeneralAsset->other_assets_inlcuded  = $request->other_assets_inlcuded;
        $ProjectGeneralAsset->type_of_location  = $request->type_of_location;
        $ProjectGeneralAsset->facilities  = $request->facilities;
        $ProjectGeneralAsset->monthly_rent  = $request->monthly_rent;
        $ProjectGeneralAsset->square_units  = $request->square_units;
        $ProjectGeneralAsset->lease_expiration  = Carbon::createFromFormat($this->global->date_format, $request->lease_expiration)->format('Y-m-d');;
        $ProjectGeneralAsset->square_units  = $request->square_units;
        $ProjectGeneralAsset->save();
        
        
        $ProjectFinance = new ProjectFinance();
        $ProjectFinance->project_id = $project_id;
        $ProjectFinance->finance_year = $request->finance_year;
        $ProjectFinance->date_source = $request->date_source;
        $ProjectFinance->total_sales = $request->total_sales;
        $ProjectFinance->cost_ship = $request->cost_ship;
        $ProjectFinance->total_expenses = $request->total_expenses;
        $ProjectFinance->owner_salary = $request->owner_salary;
        $ProjectFinance->beneficial_addblocks = $request->beneficial_addblocks;
        $ProjectFinance->interest = $request->interest;
        $ProjectFinance->depreciation = $request->depreciation;
        $ProjectFinance->other = $request->other;
        $ProjectFinance->seller_earnings = $request->seller_earnings;
        $ProjectFinance->owner_financing = $request->owner_financing;
        $ProjectFinance->owner_financing_interest = $request->owner_financing_interest;
        $ProjectFinance->ownership_months = $request->ownership_months;
        $ProjectFinance->ownership_monthly_pay = $request->ownership_monthly_pay;
        $ProjectFinance->seller_financing = $request->seller_financing;
        $ProjectFinance->assumable_financing = $request->assumable_financing;
        $ProjectFinance->assumable_finterest = $request->assumable_finterest;
        $ProjectFinance->seller_ntcomplete = $request->seller_ntcomplete;
        $ProjectFinance->no_seller_nincomplete = $request->no_seller_nincomplete;
        $ProjectFinance->commission_rate = $request->commission_rate;
        $ProjectFinance->minimum_commission = $request->minimum_commission;
        $ProjectFinance->fees_retainers = $request->fees_retainers;
        $ProjectFinance->total_commission = $request->total_commission;
        $ProjectFinance->my_ncommission = $request->my_ncommission;
        $ProjectFinance->my_ncommission_split = $request->my_ncommission_split;
        $ProjectFinance->buyable_broker = $request->buyable_broker;
        $ProjectFinance->probability_pipeline = $request->probability_pipeline;
        $ProjectFinance->probability_ofclosing = $request->probability_ofclosing;
        $ProjectFinance->noof_listed_days = $request->noof_listed_days;
        $ProjectFinance->sold_price = $request->sold_price;
        $ProjectFinance->commission = $request->commission;
        $ProjectFinance->activated_date = Carbon::createFromFormat($this->global->date_format, $request->activated_date)->format('Y-m-d');//
        $ProjectFinance->save();
        
        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $project->updateCustomFieldData($request->get('custom_fields_data'));
        }


        $this->logSearchEntry($project_id, 'Project: ' . $project->project_name, 'admin.projects.show', 'project');

        $this->logProjectActivity($project_id, ucwords($project->project_name) . ' ' . __('messages.addedAsNewProject'));

        return Reply::dataOnly(['projectID' => $project_id]);
    }



    public function destroy($id)
    {
        Project::destroy($id);
        return Reply::success(__('messages.projectDeleted'));
    }

    public function gantt($ganttProjectId = '')
    {

        $data = array();
        $links = array();

        $projects = Project::select('projects.id', 'projects.project_name', 'projects.start_date', 'projects.deadline', 'projects.completion_percent');

        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->user->id);
        }

        if ($ganttProjectId != '') {
            $projects = $projects->where('projects.id', '=', $ganttProjectId);
        }

        $projects = $projects->get();

        $id = 0; //count for gantt ids
        foreach ($projects as $project) {
            $id = $id + 1;
            $projectId = $id;

            // TODO::ProjectDeadline to do
            $projectDuration = 0;
            if ($project->deadline) {
                $projectDuration = $project->deadline->diffInDays($project->start_date);
            }

            $data[] = [
                'id' => $projectId,
                'text' => ucwords($project->project_name),
                'start_date' => $project->start_date->format('Y-m-d H:i:s'),
                'duration' => $projectDuration,
                'progress' => $project->completion_percent / 100
            ];

            $tasks = Task::projectOpenTasks($project->id)->whereNotNull('due_date');
            foreach ($tasks as $key => $task) {
                $id = $id + 1;
                    $taskDuration = $task->due_date->diffInDays($task->start_date);
                $data[] = [
                    'id' => $id,
                    'text' => ucfirst($task->heading),
                    'start_date' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d H:i:s') : $task->due_date->format('Y-m-d H:i:s'),
                    'duration' => $taskDuration ?? '',
                    'parent' => $projectId
                ];

                $links[] = [
                    'id' => $id,
                    'source' => $project->id,
                    'target' => $task->id,
                    'type' => 1
                ];
            }

            $ganttData = [
                'data' => $data,
                'links' => $links
            ];
        }

        $this->ganttProjectId = $ganttProjectId;
        $this->project = Project::findOrFail($ganttProjectId);
        return view('member.projects.gantt', $this->data);
    }

    public function ganttData($ganttProjectId = '')
    {
        $assignedTo = request('assignedTo');

        if ($assignedTo != 'all') {
            $tasks = Task::projectTasks($ganttProjectId, $assignedTo);
        } else {
            $tasks = Task::projectTasks($ganttProjectId);
        }

        
        $data = array();

        foreach ($tasks as $key => $task) {

            $data[] = [
                'id' => 'task-' . $task->id,
                'name' => ucfirst($task->heading),
                'start' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d') : $task->due_date->format('Y-m-d'),
                'end' => (!is_null($task->due_date)) ? $task->due_date->format('Y-m-d') : '',
                'progress' => 0,
                'bg_color' => $task->board_column->label_color,
                'taskid' => $task->id,
                'draggable' => true
            ];

            if (!is_null($task->dependent_task_id)) {
                $data[$key]['dependencies'] = 'task-' . $task->dependent_task_id;
            }
        }

        return response()->json($data);

        
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->addDay()->format('Y-m-d');
        $task->save();

        return Reply::success('messages.taskUpdatedSuccessfully');
    }

    public function ajaxCreate(Request $request, $projectId = null)
    {

        $this->employees  = User::allEmployees();


        $projects = Project::select('projects.*');

        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->user->id);
        }

        $projects = $projects->get();

        if ($projectId) {
            $this->employees = ProjectMember::byProject($projectId);
            $this->projectId = $projectId;
            $this->pageName = 'ganttChart';
            $this->currentProject = $projects->filter(function ($value, $key) {
                return $value->id == $this->projectId;
            })->first();
        }
        $this->taskBoardColumns = TaskboardColumn::all();

        $this->projects = $projects;
        $this->categories = TaskCategory::all();

        $this->parentGanttId = ($request->has('parent_gantt_id')) ? $request->parent_gantt_id : '';
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId)
                ->get();
        } else {
            $this->allTasks = [];
        }
        return view('member.all-tasks.ajax_create', $this->data);
    }
    
    /**
     * Project discussions
     *
     * @param  int $projectId
     * @return \Illuminate\Http\Response
     */
    public function leadInfo(MemberDiscussionDataTable $dataTable, $projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->ProjectAggrement = $ProjectAggrement = ProjectAggrement::findOrFail($projectId);;
        // $this->ProjectAggrement = ProjectAggrement::orderBy('order', 'asc')->get();
        // dd($this->data);
        return view('member.projects.aggrement', $this->data);
        // return $dataTable->with('project_id', $projectId)->render('member.projects.aggrement', $this->data);
    }

    /**
     * Project discussions
     *
     * @param  int $projectId
     * @return \Illuminate\Http\Response
     */
    public function discussion(MemberDiscussionDataTable $dataTable, $projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->discussionCategories = DiscussionCategory::orderBy('order', 'asc')->get();
        return $dataTable->with('project_id', $projectId)->render('member.projects.discussion.show', $this->data);
    }

    /**
     * Project discussions
     *
     * @param  int $projectId
     * @param  int $discussionId
     * @return \Illuminate\Http\Response
     */
    public function discussionReplies($projectId, $discussionId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->discussion = Discussion::with('category')->findOrFail($discussionId);
        $this->discussionReplies = DiscussionReply::with('user')->where('discussion_id', $discussionId)->orderBy('id', 'asc')->get();
        return view('member.projects.discussion.replies', $this->data);
    }

    /**
     * @param $templateId
     * @return mixed
     */
    public function templateData($templateId)
    {
        $templateMember  = [];
        $projectTemplate = ProjectTemplate::with('members')->findOrFail($templateId);

        if ($projectTemplate->members) {
            $templateMember  = $projectTemplate->members->pluck('user_id')->toArray();
        }

        return Reply::dataOnly(['templateData' => $projectTemplate, 'member' => $templateMember]);
    }

    /**
     * @return mixed
     */
    public function pinnedItem()
    {
        $this->pinnedItems = Pinned::join('projects', 'projects.id', '=', 'pinned.project_id')
            ->where('pinned.user_id', '=', user()->id)
            ->select('projects.id', 'project_name')
            ->get();

        return view('member.projects.pinned-project', $this->data);
    }

}
