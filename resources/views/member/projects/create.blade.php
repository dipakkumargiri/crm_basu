@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">

<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset!important;
    }
    .bootstrap-select.btn-group .dropdown-menu li a span.text {
        color: #000;
    }
    .panel-black .panel-heading a:hover, .panel-inverse .panel-heading a:hover {
        color: #000 !important;
    }
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: #000 !important;
    }
    .btn-info.active, .btn-info:active, .open>.dropdown-toggle.btn-info {
        background-color:unset !important; ;
        border-color: #269abc;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.projects.createTitle')
                    <div class="pull-right">
                        <div class="btn-group m-r-10">
                            <?php /*?>
                            <button aria-expanded="true" data-toggle="dropdown" class="btn btn-block btn-outline btn-info waves-effect dropdown-toggle waves-light" type="button">@lang('app.menu.template') <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu pull-right">
                                @forelse($templates as $template)
                                    <li onclick="setTemplate('{{$template->id}}')" role="presentation"><a href="javascript:void(0)" role="menuitem"><i class="icon wb-reply" aria-hidden="true"></i> {{ ucwords($template->project_name) }}</a></li>
                                @empty

                                @endforelse
                            </ul>
                            <?php */?>
                        </div>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createProject','class'=>'ajax-form','method'=>'POST']) !!}
                        <div class="form-body">
                            <h3 class="box-title m-b-10">@lang('modules.projects.projectInfo')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.projects.selectClient')</label>
                                        <select class="select2 form-control" name="client_id" id="client_id"
                                                data-style="form-control" onChange="getDetails()">
                                            <option value="">@lang('modules.projects.selectClient')</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.projects.projectName')
                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-info-circle"></i>
                                                <span class="tooltip-content5">
                                                    <span class="tooltip-text3">
                                                        <span class="tooltip-inner2">@lang('help_text.projectName')</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </label>
                                        <input type="text" name="project_name" id="project_name" class="form-control">
                                        <input type="hidden" name="template_id" id="template_id">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectCategory')

                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-info-circle"></i>
                                                <span class="tooltip-content5">
                                                    <span class="tooltip-text3">
                                                        <span class="tooltip-inner2">@lang('help_text.projectCategorySettings')</span>
                                                    </span>
                                                </span>
                                            </a>

                                            <a href="javascript:;" id="addProjectCategory" class="text-info"><i class="ti-settings text-info"></i> </a>
                                        </label>
                                        <select class="selectpicker form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.franchise')</label>
                                        <input type="text" name="franchise" id="franchise"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.office')</label>
                                        <input type="text" name="franchise_office" id="franchise_office"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.email')</label>
                                        <input type="email" name="email" id="email"  class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectMarketing')
                                            <a href="javascript:;" id="addProjectMarketing" class="text-info"><i class="ti-settings text-info"></i> </a>
                                        </label>
                                        <select class="selectpicker form-control" name="marketing_status_id" id="marketing_status_id"
                                                data-style="form-control">
                                            @forelse($marketing_status as $status)
                                                <option value="{{ $status->id }}">{{ ucwords($status->marketing_status) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noMarketingStatusAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.project') @lang('app.status')</label>
                                        <select name="status" id="" class="form-control">
                                            <option
                                                    value="not started">@lang('app.notStarted')
                                            </option>
                                            <option
                                                    value="in progress">@lang('app.inProgress')
                                            </option>
                                            <option
                                                    value="on hold">@lang('app.onHold')
                                            </option>
                                            <option
                                                    value="canceled">@lang('app.canceled')
                                            </option>
                                            <option
                                                    value="finished">@lang('app.finished')
                                            </option>
                                            <option        
                                                    value="under review">@lang('app.underReview')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.leadInfo')</h3>
                            <hr>
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">@lang('modules.lead.leadSource')</label>
                                        <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadSource')"  id="source_id" name="source_id" readonly>
                                            @foreach($sources as $source)
                                                <option value="{{ $source->id }}">{{ ucwords($source->type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.addedBy')</label>
                                        <input type="text" id="generated_by" name="generated_by" class="form-control"  readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.refferedBy')</label>
                                        <input type="text" name="reffered_by" id="reffered_by"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.refferedFee')</label>
                                        <input type="text" id="reffered_fee" name="reffered_fee" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agreementType')</label>
                                        <input type="text" id="agreement_type" name="agreement_type" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agencyType')</label>
                                        <input type="text" id="agency_type" name="agency_type" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.projects.startDate')
                                            <a class="mytooltip" href="javascript:void(0)">
                                                <i class="fa fa-info-circle"></i>
                                                <span class="tooltip-content5">
                                                    <span class="tooltip-text3">
                                                        <span class="tooltip-inner2">@lang('help_text.startDate')</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </label>
                                        <input type="text" name="start_date" id="start_date" autocomplete="off" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4" id="deadlineBox">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.deadline')</label>
                                        <input type="text" name="deadline" id="deadline" autocomplete="off" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.assignTo')</label></label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                                                <option value="">@lang('modules.tickets.chooseAgents')</option>
                                                @foreach($leadAgents as $emp)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessDetail')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.represented')</label>
                                        <input type="text" id="represented" name="represented" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.companyName')</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.website')</label>
                                        <input type="text" id="website" name="website" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('modules.lead.mobile')</label>
                                    <div class="form-group" style="
                                    display: flex;">
                                        <input type="tel" name="mobile" id="mobile" class="form-control" style=" border-top-left-radius: 0px;
                                        border-bottom-left-radius: 0px;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.established')</label>
                                        <input type="number" id="established" name="established" class="form-control"  maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owned')</label>
                                        <input type="number" id="owned" name="owned" class="form-control" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fromOwnership')</label>
                                        <input type="text" id="from_ownership" name="from_ownership" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.note')</label>
                                        <textarea name="comments" id="notes" rows="3" class="form-control summernote"></textarea>
                                    </div>
                                </div>

                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessSale')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.invoices.currency')</label>
                                        <select name="currency_id" id="" class="form-control select2">
                                            @foreach ($currencies as $item)
                                                <option 
                                                @if (company_setting()->currency_id == $item->id)
                                                    selected
                                                @endif
                                                value="{{ $item->id }}">{{ $item->currency_name }} ({{ $item->currency_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.listPrice')</label>
                                        <input type="number" min="0" value="0" name="project_budget" id="project_budget" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.downPayment')</label>
                                        <input type="number" min="0" value="0" name="down_payment" id="down_payment" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.vat_value')</label>
                                        <input type="number" min="0" value="0" name="vat_value" id="vat_value" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.value_tax')</label>
                                        <input type="number" min="0" value="0" name="value_tax" id="value_tax" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.realestate_price')</label>
                                        <input type="number" min="0" value="0" name="realestate_price" id="realestate_price" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.training_support')</label>
                                        <input type="text" id="training_support" name="training_support" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.nooftraining_week')</label>
                                        <input type="text" id="nooftraining_week" name="nooftraining_week" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.sale_reason')</label>
                                        <input type="text" id="sale_reason" name="sale_reason" class="form-control" >
                                    </div>
                                </div>
                                
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessWebsite')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ad_headline')</label>
                                        <input type="text" id="ad_headline" name="ad_headline" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.listing_url')</label>
                                        <input type="text" id="listing_url" name="listing_url" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.office_location')</label>
                                        <input type="text" id="office_location" name="office_location" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.promoted')</label>
                                        <input type="text" id="promoted" name="promoted" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agent_promoted')</label>
                                        <input type="text" id="agent_promoted" name="agent_promoted" class="form-control" >
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectSummary')</label>
                                        <textarea name="project_summary" id="project_summary"
                                                  class="summernote"></textarea>
                                    </div>
                                </div>

                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.commentsInstructions')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.business_history')</label>
                                        <input type="text" id="business_history" name="business_history" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.competitive_overview')</label>
                                        <input type="text" id="competitive_overview" name="competitive_overview" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.potential_growth')</label>
                                        <input type="text" id="potential_growth" name="potential_growth" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.showing_instruction')</label>
                                        <input type="text" id="showing_instruction" name="showing_instruction" class="form-control" >
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.showing_comments')</label>
                                        <textarea name="showing_comments" id="showing_comments"
                                                  class="summernote"></textarea>
                                    </div>
                                </div>

                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.geogrpahicalLocation')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.general_location')</label>
                                        <input type="text" id="general_location" name="general_location" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.gmap_url')</label>
                                        <input type="text" id="gmap_url" name="gmap_url" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.address')</label>
                                        <input type="text" id="address" name="address" class="form-control" >
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.post_code')</label>
                                        <input type="text" id="post_code" name="post_code" class="form-control" >
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4 ">
                                        <div class="form-group">
                                            <div class="form-group">
                                              <label>@lang('modules.stripeCustomerAddress.country')</label>
                                                <select class="select2 form-control" name="country_id" id="cog_countries_id"
                                                data-style="form-control">
                                                    @forelse($Allcountries as $coun)
                                                    <option value="{{ $coun->id }}">{{ ucwords($coun->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">@lang('modules.stripeCustomerAddress.state')
                                                
                                        </label>
                                        <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.stripeCustomerAddress.state')"  id="state_id" name="state_id">                                                 
                                      
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">@lang('modules.stripeCustomerAddress.city')
                                                
                                        </label>
                                        <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.stripeCustomerAddress.city')"  id="city_id" name="city_id">                                                 
                                      
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.generalOperations')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.business_hours')</label>
                                        <input type="text" id="business_hours" name="business_hours" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.weekly_hours')</label>
                                        <input type="number" min="0" value="0" name="weekly_hours" id="weekly_hours" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.relocation')</label>
                                        <input type="text" id="relocation" name="relocation" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.franchisee_operations')</label>
                                        <input type="text" id="franchisee_operations" name="franchisee_operations" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.franchise_mart')</label>
                                        <input type="text" id="franchise_mart" name="franchise_mart" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.home_based')</label>
                                        <input type="text" id="home_based" name="home_based" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.no_of_emp')</label>
                                        <input type="number" min="0" value="0" name="no_of_emp" id="no_of_emp" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.assets')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.inventory_value')</label>
                                        <input type="text" id="inventory_value" name="inventory_value" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ff_value')</label>
                                        <input type="text" id="ff_value" name="ff_value" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.accounts_recieveable')</label>
                                        <input type="text" id="accounts_recieveable" name="accounts_recieveable" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.leashold')</label>
                                        <input type="text" id="leashold" name="leashold" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value')</label>
                                        <input type="text" id="estate_value" name="estate_value" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.other_assets')</label>
                                        <input type="text" id="other_assets" name="other_assets" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_assets')</label>
                                        <input type="text" id="total_assets" name="total_assets" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.inventory_inlcuded')</label>
                                        <input type="text" id="inventory_inlcuded" name="inventory_inlcuded" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fe_inlcuded')</label>
                                        <input type="text" id="fe_inlcuded" name="fe_inlcuded" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.accounts_recieveable_include')</label>
                                        <input type="text" id="accounts_recieveable_include" name="accounts_recieveable_include" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.leashold_include')</label>
                                        <input type="text" id="leashold_include" name="leashold_include" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value_available')</label>
                                        <input type="text" id="estate_value_available" name="estate_value_available" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value_include')</label>
                                        <input type="text" id="estate_value_include" name="estate_value_include" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.other_assets_inlcuded')</label>
                                        <input type="text" id="other_assets_inlcuded" name="other_assets_inlcuded" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.lease')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.type_of_location')</label>
                                        <input type="text" id="type_of_location" name="type_of_location" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.facilities')</label>
                                        <input type="text" id="facilities" name="facilities" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.monthly_rent')</label>
                                        <input type="number" min="0" value="0" name="monthly_rent" id="monthly_rent" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.square_units')</label>
                                        <input type="text" id="square_units" name="square_units" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.lease_expiration')</label>
                                        <input type="text" id="lease_expiration" name="lease_expiration" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.primaryFinancials')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.year')</label>
                                        <input type="text" id="finance_year" name="finance_year" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.date_source')</label>
                                        <input type="text" id="date_source" name="date_source" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_sales')</label>
                                        <input type="text" id="total_sales" name="total_sales" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.cost_ship')</label>
                                        <input type="text" id="cost_ship" name="cost_ship" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_expenses')</label>
                                        <input type="text" id="total_expenses" name="total_expenses" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_salary')</label>
                                        <input type="text" id="owner_salary" name="owner_salary" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.beneficial_addblocks')</label>
                                        <input type="text" id="beneficial_addblocks" name="beneficial_addblocks" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Interest</label>
                                        <input type="text" id="interest" name="interest" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Depreciation</label>
                                        <input type="text" id="depreciation" name="depreciation" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Other</label>
                                        <input type="text" id="other" name="other" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Seller Depreciation Earnings</label>
                                        <input type="text" id="seller_earnings" name="seller_earnings" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.financing')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_financing')</label>
                                        <input type="text" id="owner_financing" name="owner_financing" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_financing_interest')</label>
                                        <input type="text" id="owner_financing_interest" name="owner_financing_interest" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ownership_months')</label>
                                        <input type="text" id="ownership_months" name="ownership_months" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ownership_monthly_pay')</label>
                                        <input type="number" min="0" value="0" name="ownership_monthly_pay" id="ownership_monthly_pay" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.seller_financing')</label>
                                        <input type="number" min="0" value="0" name="seller_financing" id="seller_financing" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.assumable_financing')</label>
                                        <input type="number" min="0" value="0" name="assumable_financing" id="assumable_financing" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.assumable_finterest')</label>
                                        <input type="text" id="assumable_finterest" name="assumable_finterest" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.nonCompete')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.seller_ntcomplete')</label>
                                        <input type="text" id="seller_ntcomplete" name="seller_ntcomplete" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.no_seller_nincomplete')</label>
                                        <input type="text" id="no_seller_nincomplete" name="no_seller_nincomplete" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="box-title m-b-10">@lang('modules.projects.pipleLineCommission')</h3>
                            <hr>
                            <div class="row">    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.commission_rate')</label>
                                        <input type="number" min="0" value="0" name="commission_rate" id="commission_rate" class="form-control" step=".01">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.minimum_commission')</label>
                                        <input type="text" id="minimum_commission" name="minimum_commission" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fees_retainers')</label>
                                        <input type="text" id="fees_retainers" name="fees_retainers" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_commission')</label>
                                        <input type="text" id="total_commission" name="total_commission" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.my_ncommission')</label>
                                        <input type="text" id="my_ncommission" name="my_ncommission" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.my_ncommission_split')</label>
                                        <input type="text" id="my_ncommission_split" name="my_ncommission_split" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.buyable_broker')</label>
                                        <input type="text" id="buyable_broker" name="buyable_broker" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.probability_pipeline')</label>
                                        <input type="text" id="probability_pipeline" name="probability_pipeline" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.probability_ofclosing')</label>
                                        <input type="text" id="probability_ofclosing" name="probability_ofclosing" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.soldInformation')</h3>
                            <hr>
                            <div class="row">    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.activated_date')</label>
                                        <input type="text" id="activated_date" name="activated_date" class="form-control" >
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.noof_listed_days')</label>
                                        <input type="number" min="0" value="0" name="noof_listed_days" id="noof_listed_days" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.sold_price')</label>
                                        <input type="number" min="0" value="0" name="sold_price" id="sold_price" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.commission')</label>
                                        <input type="text" id="commission" name="commission" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <?php /*?>
                            <div class="row">
                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.projectName')</label>
                                        <input type="text" name="project_name" id="project_name" class="form-control">
                                        <input type="hidden" name="template_id" id="template_id">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectCategory')</label>
                                        <select class="selectpicker form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noProjectCategoryAdded')</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.selectClient')</label>
                                        <select class="select2 form-control" name="client_id" id="client_id"
                                                data-style="form-control">
                                                <option value="">@lang('modules.projects.selectClient')</option>
                                            @forelse($clients as $client)
                                                <option value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-md-5">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info  col-md-10">
                                            <input id="client_view_task" onchange="checkTask()" name="client_view_task" value="true"
                                                   type="checkbox">
                                            <label for="client_view_task">@lang('modules.projects.clientViewTask')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3" id="clientNotification">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info  col-md-10">
                                            <input id="client_task_notification" name="client_task_notification" value="true"
                                                   type="checkbox">
                                            <label for="client_task_notification">@lang('modules.projects.clientTaskNotification')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="checkbox checkbox-info  col-md-10">
                                            <input id="manual_timelog" name="manual_timelog" value="true"
                                                   type="checkbox">
                                            <label for="manual_timelog">@lang('modules.projects.manualTimelog')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" id="user_id">
                                        <label class="required">@lang('modules.projects.addMemberTitle')</label>
                                        <a href="javascript:;" id="add-employee" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>
                                        <select class="select2 m-b-10 select2-multiple " id="selectEmployee" multiple="multiple"
                                                data-placeholder="Choose Members" name="user_id[]">
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                                        (YOU) @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.startDate')</label>
                                        <input type="text" name="start_date" autocomplete="off" id="start_date" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-4" id="deadlineBox">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.deadline')</label>
                                        <input type="text" name="deadline" autocomplete="off" id="deadline" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group" style="padding-top: 25px;">
                                        <div class="checkbox checkbox-info">
                                            <input id="without_deadline" name="without_deadline" value="true"
                                                   type="checkbox">
                                            <label for="without_deadline">@lang('modules.projects.withoutDeadline')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectSummary')</label>
                                        <textarea name="project_summary" id="project_summary"
                                                  class="summernote"></textarea>
                                    </div>
                                </div>

                            </div>
                            <!--/span-->
                            
        
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.note')</label>
                                        <textarea name="notes" id="notes" rows="5" class="form-control summernote"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.project') @lang('app.status')</label>
                                        <select name="status" id="" class="form-control">
                                            <option
                                                    value="not started">@lang('app.notStarted')
                                            </option>
                                            <option
                                                    value="in progress">@lang('app.inProgress')
                                            </option>
                                            <option
                                                    value="on hold">@lang('app.onHold')
                                            </option>
                                            <option
                                                    value="canceled">@lang('app.canceled')
                                            </option>
                                            <option
                                                    value="finished">@lang('app.finished')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php */?>
                            <!--/span-->
                            <h3 class="box-title m-b-10">@lang('modules.projects.WebsiteImage')</h3>
                            <hr>
                            <div class="col-xs-12 col-md-5">
                                <div class="form-group">
                                    <div class="checkbox checkbox-info  col-md-10">
                                        <input id="client_view_task" onchange="checkTask()" name="has_image" value="true" type="checkbox">
                                        <label for="client_view_task">@lang('modules.projects.has_image')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-20"  id="clientNotification" style="display: none;">
                                <div class="col-xs-12">
                                    @if($upload)
                                    <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                    <div id="file-upload-box" >
                                        <div class="row" id="file-dropzone">
                                            <div class="col-xs-12">
                                                <div class="dropzone"
                                                     id="file-upload-dropzone">
                                                    {{ csrf_field() }}
                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple/>
                                                    </div>
                                                    <input name="image_url" id="image_url"type="hidden" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="projectID" id="projectID">
                                    @else
                                        <div class="alert alert-danger">@lang('messages.storageLimitExceedContactAdmin')</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row m-b-20" id="clientNotification" style="display: none;">
                                <div class="col-xs-12">
                                    @if($upload)
                                    <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                    <div id="file-upload-box" >
                                        <div class="row" id="file-dropzone">
                                            <div class="col-xs-12">
                                                <div class="dropzone"
                                                     id="file-upload-dropzone">
                                                    {{ csrf_field() }}
                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple/>
                                                    </div>
                                                    <input name="image_url" id="image_url"type="hidden" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="taskIDField" id="taskIDField">
                                    <input type="hidden" name="lead_id" id="lead_id">
                                    @else
                                        <div class="alert alert-danger">@lang('messages.storageLimitExceed', ['here' => '<a href='.route('admin.billing.packages'). '>Here</a>'])</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                @if(isset($fields))
                                    @foreach($fields as $field)
                                        <div class="col-md-6">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                @elseif($field->type == 'radio')
                                                    <div class="radio-list">
                                                        @foreach($field->values as $key=>$value)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($editUser) && $editUser->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type == 'select')
                                                    {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                            $field->values,
                                                             isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                     !!}

                                                @elseif($field->type == 'checkbox')
                                                <div class="mt-checkbox-inline custom-checkbox checkbox-{{$field->id}}">
                                                    <input type="hidden" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" 
                                                    id="{{$field->name.'_'.$field->id}}" value=" ">
                                                    @foreach($field->values as $key => $value)
                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                            <input name="{{$field->name.'_'.$field->id}}[]"
                                                                   type="checkbox" onchange="checkboxChange('checkbox-{{$field->id}}', '{{$field->name.'_'.$field->id}}')" value="{{$value}}"> {{$value}}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @elseif($field->type == 'date')
                                                    <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                           value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                                @endif
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            
            
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"><i class="fa fa-check"></i>
                                @lang('app.save')
                            </button>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.js') }}"></script>

<script>
    function checkboxChange(parentClass, id){
        var checkedData = '';
        $('.'+parentClass).find("input[type= 'checkbox']:checked").each(function () {
            if(checkedData !== ''){
                checkedData = checkedData+', '+$(this).val();
            }
            else{
                checkedData = $(this).val();
            }
        });
        $('#'+id).val(checkedData);
    }

    projectID = '';
    @if($upload)
    Dropzone.autoDiscover = false;
    //Dropzone class
    myDropzone = new Dropzone("div#file-upload-dropzone", {
        url: "{{ route('member.files.multiple-upload') }}",
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        paramName: "file",
        maxFilesize: 10,
        maxFiles: 10,
        acceptedFiles: "image/*,application/pdf",
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks:true,
        parallelUploads:10,
        dictDefaultMessage: "@lang('modules.projects.dropFile')",
        init: function () {
            myDropzone = this;
            this.on("success", function (file, response) {
                if(response.status == 'fail') {
                    $.showToastr(response.message, 'error');
                    return;
                }
            })
        }
    });
    myDropzone.on('sending', function(file, xhr, formData) {
        console.log([formData, 'formData']);
        var ids = $('#projectID').val();
        formData.append('project_id', ids);
    });
    myDropzone.on('completemultiple', function () {
        var msgs = "@lang('modules.projects.projectUpdated')";
        $.showToastr(msgs, 'success');
        window.location.href = '{{ route('member.projects.index') }}'
    });
    @endif

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('#clientNotification').hide();

    $("#start_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#deadline').datepicker('setStartDate', minDate);
    });

    // check client view task checked
    function checkTask()
    {
        var chVal = $('#client_view_task').is(":checked") ? true : false;
        if(chVal == true){
            $('#clientNotification').show();
        }
        else{
            $('#clientNotification').hide();
        }

    }

    $('#without_deadline').click(function () {
        var check = $('#without_deadline').is(":checked") ? true : false;
        if(check == true){
            $('#deadlineBox').hide();
        }
        else{
            $('#deadlineBox').show();
        }
    });

    // Set selected Template
    function setTemplate(id){
        var url = "{{ route('member.projects.template-data',':id') }}";
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#createProject',
            type: "GET",
            success: function(response){
                var selectedTemplate = [];
                if(id != null && id != undefined && id != ""){
                    selectedTemplate = response.templateData;

                    if(response.member){
                        $('#selectEmployee').val(response.member);
                        $('#selectEmployee').trigger('change');
                    }

                    $('#project_name').val(selectedTemplate['project_name']);
                    $('#category_id').selectpicker('val', selectedTemplate['category_id']);
                    $('#project_summary').summernote('code', selectedTemplate['project_summary']);
                    $('#notes').summernote('code', selectedTemplate['notes']);
                    $('#template_id').val(selectedTemplate['id']);

                    if(selectedTemplate['client_view_task'] == 'enable'){
                        $("#client_view_task").prop('checked', true);
                        $('#clientNotification').show();
                        if(selectedTemplate['allow_client_notification'] == 'enable'){
                            $("#client_task_notification").prop('checked', 'checked');
                        }
                        else{
                            $("#client_task_notification").prop('checked', false);
                        }
                    }
                    else{
                        $("#client_view_task").prop('checked', false);
                        $("#client_task_notification").prop('checked', false);
                        $('#clientNotification').hide();
                    }
                    if(selectedTemplate['manual_timelog'] == 'enable'){
                        $("#manual_timelog").prop('checked', true);
                    }
                    else{
                        $("#manual_timelog").prop('checked', false);
                    }
                }
            }
        })

    }

    $("#deadline").datepicker({
                autoclose: true,
        format: '{{ $global->date_picker_format }}',
    })

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.projects.store')}}',
            container: '#createProject',
            type: "POST",
            redirect: true,
            data: $('#createProject').serialize(),
            success: function(response){
                var dropzone = 0;
                @if($upload)
                    dropzone = myDropzone.getQueuedFiles().length;
                @endif

                if(dropzone > 0){
                    $('#projectID').val(response.projectID);
                    myDropzone.processQueue();
                }
                else{
                    var msgs = "@lang('modules.projects.projectUpdated')";
                    $.showToastr(msgs, 'success');
                    window.location.href = '{{ route('member.projects.index') }}'
                }
            }
        })
    });

    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ["view", ["fullscreen"]]
        ]
    });

    $(':reset').on('click', function(evt) {
        evt.preventDefault()
        $form = $(evt.target).closest('form')
        $form[0].reset()
        $form.find('select').selectpicker('render')
        $form.find('select').select2()
    });




    function getDetails(){
        const id = $('#client_id').val();
        
        var url = "{{ route('admin.projects.lead-data',':id') }}";
        url = url.replace(':id', id);
        $.easyAjax({
            url: url,
            container: '#createProject',
            type: "GET",
            success: function(response){
                console.log(response);
                var selectedTemplate = [];
                if(id != null && id != undefined && id != ""){
                    selectedTemplate = response.leadDetail;

                    if(response.member){
                        $('#selectEmployee').val(response.member);
                        $('#selectEmployee').trigger('change');
                    }

                    $('#generated_by').val(selectedTemplate['created_by_name']);
                    $('#email').val(selectedTemplate['client_email']);
                    $('#company_name').val(selectedTemplate['company_name']);
                    $('#website').val(selectedTemplate['website']);
                    $('#mobile').val(selectedTemplate['mobile']);
                    $('#lead_id').val(selectedTemplate['id']);
                    $('#agent_id').select2('val', selectedTemplate['agent_id']);
                    $('#source_id').select2('val', selectedTemplate['source_id']);
                    $('#category_id').selectpicker('val', selectedTemplate['category_id']);
                    $('#project_summary').summernote('code', selectedTemplate['project_summary']);
                    $('#notes').summernote('code', selectedTemplate['notes']);
                    $('#template_id').val(selectedTemplate['id']);

                    
                }
            }
        })

    }


    $('#cog_countries_id').on('change',function(){
        // alert($(this).val());
        var country_id = $(this).val();
        getState(country_id);
    })
    function getState(country_id){
        var url = "{{route('admin.leads.getState')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
        url: url,
        type: "POST",
        data: {'_token': token, country_id: country_id},
        success: function (data) {
            console.log(data);
            var options = [];
            var rData = [];
            rData = data.AllStates;
            $.each(rData, function( index, value ) {
                var selectData = '';
                selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                options.push(selectData);
            });
            $('#state_id').html(options);
            $('#state_id').selectpicker('refresh');

        }
    })
    }
    
    $('#state_id').on('change',function(){
        // alert($(this).val());
        var state_id = $(this).val();
        getCity(state_id);
    })
    function getCity(state_id){
            var url = "{{route('admin.leads.getCity')}}";
            var token = "{{ csrf_token() }}";
            $.easyAjax({
            url: url,
            type: "POST",
            data: {'_token': token, state_id: state_id},
            success: function (data) {
                console.log(data);
                var options = [];
                var rData = [];
                rData = data.AllCities;
                $.each(rData, function( index, value ) {
                    var selectData = '';
                    selectData = '<option value="'+value.id+'">'+value.name+'</option>';
                    options.push(selectData);
                });
                $('#city_id').html(options);
                $('#city_id').selectpicker('refresh');

            }
        })
    }
</script>

@endpush

