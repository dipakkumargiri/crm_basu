@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

<link rel="stylesheet" href="{{ asset('plugins/bower_components/ion-rangeslider/css/ion.rangeSlider.css') }}">
<link rel="stylesheet"
      href="{{ asset('plugins/bower_components/ion-rangeslider/css/ion.rangeSlider.skinModern.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset!important;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.projects.updateTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateProject','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body ">
                            <h3 class="box-title m-b-10">@lang('modules.projects.projectInfo')</h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.projects.selectClient')</label>
                                        <select class="select2 form-control" name="client_id" id="client_id"
                                                data-style="form-control" onChange="getDetails()">
                                            <option value="">@lang('modules.projects.selectClient')</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}"
                                            @if($project->client_id == $client->id)
                                            selected
                                            @endif
                                            >{{ ucwords($client->name) }}</option>
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
                                        <input type="text" name="project_name" id="project_name" class="form-control" value="{{ $project->project_name }}">
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
                                        </label>
                                        <select class="selectpicker form-control" name="category_id" id="category_id"
                                                data-style="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}"
                                                @if($project->category_id == $category->id)
                                                selected
                                                @endif
                                                >{{ ucwords($category->category_name) }}</option>
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
                                        <input type="text" name="franchise" id="franchise"  class="form-control" value="{{ $project->franchise }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.office')</label>
                                        <input type="text" name="franchise_office" id="franchise_office"  class="form-control" value="{{ $project->franchise_office }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.email')</label>
                                        <input type="email" name="email" id="email"  class="form-control" readonly value="{{ $project->email }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-xs-12 col-md-4 ">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectMarketing')
                                        </label>
                                        <select class="selectpicker form-control" name="marketing_status_id" id="marketing_status_id"
                                                data-style="form-control">
                                            @forelse($marketing_status as $status)
                                                <option value="{{ $status->id }}"
                                                @if($project->marketing_status_id == $status->id)
                                                        selected
                                                        @endif
                                                >{{ ucwords($status->marketing_status) }}</option>
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
                                                    @if($project->status == 'not started') selected @endif
                                            value="not started">@lang('app.notStarted')
                                            </option>
                                            <option
                                                    @if($project->status == 'in progress') selected @endif
                                            value="in progress">@lang('app.inProgress')
                                            </option>
                                            <option
                                                    @if($project->status == 'on hold') selected @endif
                                            value="on hold">@lang('app.onHold')
                                            </option>
                                            <option
                                                    @if($project->status == 'canceled') selected @endif
                                            value="canceled">@lang('app.canceled')
                                            </option>
                                            <option
                                                    @if($project->status == 'finished') selected @endif
                                            value="finished">@lang('app.finished')
                                            </option>
                                            <option
                                                    @if($project->status == 'under review') selected @endif
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
                                                <option value="{{ $source->id }}"
                                                @if($source->id == $project->source_id) selected @endif
                                                >{{ ucwords($source->type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.addedBy')</label>
                                        <input type="text" id="generated_by" name="generated_by" class="form-control"  readonly value="{{ $project->generated_by }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.refferedBy')</label>
                                        <input type="text" name="reffered_by" id="reffered_by"  class="form-control" value="{{ $project->aggrement->reffered_by }}">
                                        <input type="hidden" id='aggrement_id' name="aggrement_id" value="{{ $project->aggrement->id }}">
                                        <input type="hidden" id='business_id' name="business_id" value="{{ $project->business->id }}">
                                        <input type="hidden" id='detail_id' name="detail_id" value="{{ $project->detail->id }}">
                                        <input type="hidden" id='asset_id' name="asset_id" value="{{ $project->asset->id }}">
                                        <input type="hidden" id='finance_id' name="finance_id" value="{{ $project->finance->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.refferedFee')</label>
                                        <input type="text" id="reffered_fee" name="reffered_fee" class="form-control"  value="{{ $project->aggrement->reffered_fee }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agreementType')</label>
                                        <input type="text" id="agreement_type" name="agreement_type" class="form-control"  value="{{ $project->aggrement->agreement_type }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agencyType')</label>
                                        <input type="text" id="agency_type" name="agency_type" class="form-control" value="{{ $project->aggrement->agency_type }}" >
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
                                        <input type="text" name="start_date" id="start_date" autocomplete="off" class="form-control" value="{{ $project->start_date->format($global->date_format) }}">
                                    </div>
                                </div>
                                <div class="col-md-4" id="deadlineBox">
                                    <div class="form-group">
                                        <label>@lang('modules.projects.deadline')</label>
                                        <input type="text" name="deadline" id="deadline" autocomplete="off" class="form-control" value="@if($project->deadline){{ $project->deadline->format($global->date_format) }}@else {{ \Carbon\Carbon::now()->format($global->date_format) }} @endif">
                                    </div>
                                </div>
                                <?php /*?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('app.assignTo')</label></label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                                                <option value="">@lang('modules.tickets.chooseAgents')</option>
                                                @foreach($leadAgents as $emp)
                                                    <option value="{{ $emp->id }}"
                                                    @if($project->agent_id == $emp->id)
                                                        selected
                                                        @endif
                                                    >{{ ucwords($emp->user->name) }} </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <?php */?>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessDetail')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.represented')</label>
                                        <input type="text" id="represented" name="represented" class="form-control" value="{{ $project->business->represented }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.companyName')</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control" readonly value="{{ $project->company_name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.lead.website')</label>
                                        <input type="text" id="website" name="website" class="form-control" readonly value="{{ $project->business->website }}">
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
                                        <input type="number" id="established" name="established" class="form-control"  maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{ $project->business->established }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owned')</label>
                                        <input type="number" id="owned" name="owned" class="form-control" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  value="{{ $project->business->owned }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fromOwnership')</label>
                                        <input type="text" id="from_ownership" name="from_ownership" class="form-control" value="{{ $project->business->from_ownership }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.note')</label>
                                        <textarea name="comments" id="notes" rows="3" class="form-control summernote"> {{ $project->business->comments }}</textarea>
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
                                            <option value="">--</option>
                                            @foreach ($currencies as $item)
                                                <option
                                                @if($item->id == $project->currency_id) selected @endif
                                                value="{{ $item->id }}">{{ $item->currency_name }} ({{ $item->currency_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectBudget')</label>
                                        <input type="text" class="form-control" name="project_budget" value="{{ $project->project_budget }}">
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.downPayment')</label>
                                        <input type="number" min="0" name="down_payment" id="down_payment" class="form-control" step=".01" value="{{ $project->business->down_payment }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.vat_value')</label>
                                        <input type="number" min="0" name="vat_value" id="vat_value" class="form-control" step=".01" value="{{ $project->business->vat_value }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.value_tax')</label>
                                        <input type="number" min="0" name="value_tax" id="value_tax" class="form-control" step=".01" value="{{ $project->business->value_tax }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.realestate_price')</label>
                                        <input type="number" min="0" name="realestate_price" id="realestate_price" class="form-control" step=".01" value="{{ $project->business->realestate_price }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.training_support')</label>
                                        <input type="text" id="training_support" name="training_support" class="form-control" value="{{ $project->business->training_support }}" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.nooftraining_week')</label>
                                        <input type="text" id="nooftraining_week" name="nooftraining_week" class="form-control" value="{{ $project->business->nooftraining_week }}" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.sale_reason')</label>
                                        <input type="text" id="sale_reason" name="sale_reason" class="form-control" value="{{ $project->business->sale_reason }}" >
                                    </div>
                                </div>
                                
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessWebsite')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ad_headline')</label>
                                        <input type="text" id="ad_headline" name="ad_headline" class="form-control" value="{{ $project->detail->ad_headline }}" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.listing_url')</label>
                                        <input type="text" id="listing_url" name="listing_url" class="form-control" value="{{ $project->detail->listing_url }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.office_location')</label>
                                        <input type="text" id="office_location" name="office_location" class="form-control" value="{{ $project->detail->office_location }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.promoted')</label>
                                        <input type="text" id="promoted" name="promoted" class="form-control" value="{{ $project->detail->promoted }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.agent_promoted')</label>
                                        <input type="text" id="agent_promoted" name="agent_promoted" class="form-control" value="{{ $project->detail->agent_promoted }}">
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.projectSummary')</label>
                                        <textarea name="project_summary" id="project_summary"
                                                  class="summernote">{{ $project->detail->project_summary }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.commentsInstructions')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.business_history')</label>
                                        <input type="text" id="business_history" name="business_history" class="form-control" value="{{ $project->detail->business_history }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.competitive_overview')</label>
                                        <input type="text" id="competitive_overview" name="competitive_overview" class="form-control" value="{{ $project->detail->competitive_overview }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.potential_growth')</label>
                                        <input type="text" id="potential_growth" name="potential_growth" class="form-control" value="{{ $project->detail->potential_growth }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.showing_instruction')</label>
                                        <input type="text" id="showing_instruction" name="showing_instruction" class="form-control" value="{{ $project->detail->showing_instruction }}">
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.showing_comments')</label>
                                        <textarea name="showing_comments" id="showing_comments"
                                                  class="summernote">{{ $project->detail->showing_comments }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.geogrpahicalLocation')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.general_location')</label>
                                        <input type="text" id="general_location" name="general_location" class="form-control" value="{{ $project->detail->general_location }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.gmap_url')</label>
                                        <input type="text" id="gmap_url" name="gmap_url" class="form-control" value="{{ $project->detail->gmap_url }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.address')</label>
                                        <input type="text" id="address" name="address" class="form-control" value="{{ $project->detail->address }}">
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.post_code')</label>
                                        <input type="text" id="post_code" name="post_code" class="form-control" value="{{ $project->detail->post_code }}">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                               
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <div class="form-group">
                                          <label>@lang('modules.stripeCustomerAddress.country')</label>
                                            <select class="select2 form-control" name="cog_countries_id" id="cog_countries_id"
                                            data-style="form-control">
                                                @forelse($Allcountries as $coun)
                                                <option value="{{ $coun->id }}"
                                                    @if($project->detail->country_id == $coun->id)
                                                    selected
                                                    @endif>{{ ucwords($coun->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                     <label>@lang('modules.stripeCustomerAddress.state')</label>
                                   
                                      <select class="select2 form-control" data-placeholder="@lang('modules.stripeCustomerAddress.state')"  id="cog_state_id" name="cog_state_id">
                                      @forelse($AllStates as $stat)
                                     
                                       <option @if( $stat->id == $project->detail->state_id) selected @endif value="{{ $stat->id }}">{{ ucwords($stat->name) }}</option>
                                         @empty
                                         <option value="">@lang('messages.noStateAdded')</option>
                                        @endforelse                            
                                     </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.city')</label>
                                         <select class="select2 form-control" data-placeholder="@lang('modules.stripeCustomerAddress.city')"  id="cog_city_id" name="cog_city_id">
                                          @forelse($AllCities as $value)
                                         
                                           <option @if( $value->id == $project->detail->city_id) selected @endif value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                             @empty
                                             <option value="">@lang('messages.noCityAdded')</option>
                                            @endforelse                            
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
                                        <input type="text" id="business_hours" name="business_hours" class="form-control" value="{{ $project->asset->business_hours }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.weekly_hours')</label>
                                        <input type="number" min="0" name="weekly_hours" id="weekly_hours" class="form-control" value="{{ $project->asset->weekly_hours }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.relocation')</label>
                                        <input type="text" id="relocation" name="relocation" class="form-control" value="{{ $project->asset->relocation }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.franchisee_operations')</label>
                                        <input type="text" id="franchisee_operations" name="franchisee_operations" class="form-control" value="{{ $project->asset->franchisee_operations }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.franchise_mart')</label>
                                        <input type="text" id="franchise_mart" name="franchise_mart" class="form-control" value="{{ $project->asset->franchise_mart }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.home_based')</label>
                                        <input type="text" id="home_based" name="home_based" class="form-control" value="{{ $project->asset->home_based }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.no_of_emp')</label>
                                        <input type="number" min="0" name="no_of_emp" id="no_of_emp" class="form-control" value="{{ $project->asset->no_of_emp }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.assets')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.inventory_value')</label>
                                        <input type="text" id="inventory_value" name="inventory_value" class="form-control" value="{{ $project->asset->inventory_value }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ff_value')</label>
                                        <input type="text" id="ff_value" name="ff_value" class="form-control" value="{{ $project->asset->ff_value }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.accounts_recieveable')</label>
                                        <input type="text" id="accounts_recieveable" name="accounts_recieveable" class="form-control" value="{{ $project->asset->accounts_recieveable }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.leashold')</label>
                                        <input type="text" id="leashold" name="leashold" class="form-control" value="{{ $project->asset->leashold }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value')</label>
                                        <input type="text" id="estate_value" name="estate_value" class="form-control" value="{{ $project->asset->estate_value }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.other_assets')</label>
                                        <input type="text" id="other_assets" name="other_assets" class="form-control" value="{{ $project->asset->other_assets }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_assets')</label>
                                        <input type="text" id="total_assets" name="total_assets" class="form-control" value="{{ $project->asset->total_assets }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.inventory_inlcuded')</label>
                                        <input type="text" id="inventory_inlcuded" name="inventory_inlcuded" class="form-control" value="{{ $project->asset->inventory_inlcuded }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fe_inlcuded')</label>
                                        <input type="text" id="fe_inlcuded" name="fe_inlcuded" class="form-control" value="{{ $project->asset->fe_inlcuded }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.accounts_recieveable_include')</label>
                                        <input type="text" id="accounts_recieveable_include" name="accounts_recieveable_include" class="form-control" value="{{ $project->asset->accounts_recieveable_include }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.leashold_include')</label>
                                        <input type="text" id="leashold_include" name="leashold_include" class="form-control" value="{{ $project->asset->leashold_include }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value_available')</label>
                                        <input type="text" id="estate_value_available" name="estate_value_available" class="form-control" value="{{ $project->asset->estate_value_available }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.estate_value_include')</label>
                                        <input type="text" id="estate_value_include" name="estate_value_include" class="form-control" value="{{ $project->asset->estate_value_include }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.other_assets_inlcuded')</label>
                                        <input type="text" id="other_assets_inlcuded" name="other_assets_inlcuded" class="form-control" value="{{ $project->asset->other_assets_inlcuded }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.lease')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.type_of_location')</label>
                                        <input type="text" id="type_of_location" name="type_of_location" class="form-control" value="{{ $project->asset->type_of_location }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.facilities')</label>
                                        <input type="text" id="facilities" name="facilities" class="form-control" value="{{ $project->asset->facilities }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.monthly_rent')</label>
                                        <input type="number" min="0" value="{{ $project->asset->monthly_rent }}" name="monthly_rent" id="monthly_rent" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.square_units')</label>
                                        <input type="text" id="square_units" name="square_units" class="form-control" value="{{ $project->asset->square_units }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.lease_expiration')</label>
                                        <input type="text" id="lease_expiration" name="lease_expiration" class="form-control" value="{{ $project->asset->lease_expiration->format($global->date_format) }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.primaryFinancials')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.year')</label>
                                        <input type="text" id="finance_year" name="finance_year" class="form-control" value="{{ $project->finance->finance_year }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.date_source')</label>
                                        <input type="text" id="date_source" name="date_source" class="form-control" value="{{ $project->finance->date_source }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_sales')</label>
                                        <input type="text" id="total_sales" name="total_sales" class="form-control" value="{{ $project->finance->total_sales }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.cost_ship')</label>
                                        <input type="text" id="cost_ship" name="cost_ship" class="form-control" value="{{ $project->finance->cost_ship }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_expenses')</label>
                                        <input type="text" id="total_expenses" name="total_expenses" class="form-control" value="{{ $project->finance->total_expenses }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_salary')</label>
                                        <input type="text" id="owner_salary" name="owner_salary" class="form-control" value="{{ $project->finance->owner_salary }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.beneficial_addblocks')</label>
                                        <input type="text" id="beneficial_addblocks" name="beneficial_addblocks" class="form-control" value="{{ $project->finance->beneficial_addblocks }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Interest</label>
                                        <input type="text" id="interest" name="interest" class="form-control" value="{{ $project->finance->interest }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Depreciation</label>
                                        <input type="text" id="depreciation" name="depreciation" class="form-control"  value="{{ $project->finance->depreciation }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Other</label>
                                        <input type="text" id="other" name="other" class="form-control" value="{{ $project->finance->other }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Seller Depreciation Earnings</label>
                                        <input type="text" id="seller_earnings" name="seller_earnings" class="form-control" value="{{ $project->finance->seller_earnings }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.financing')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_financing')</label>
                                        <input type="text" id="owner_financing" name="owner_financing" class="form-control" value="{{ $project->finance->owner_financing }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.owner_financing_interest')</label>
                                        <input type="text" id="owner_financing_interest" name="owner_financing_interest" class="form-control" value="{{ $project->finance->owner_financing_interest }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ownership_months')</label>
                                        <input type="text" id="ownership_months" name="ownership_months" class="form-control" value="{{ $project->finance->ownership_months }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.ownership_monthly_pay')</label>
                                        <input type="number" min="0" value="{{ $project->finance->ownership_monthly_pay }}" name="ownership_monthly_pay" id="ownership_monthly_pay" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.seller_financing')</label>
                                        <input type="number" min="0" value="{{ $project->finance->seller_financing }}" name="seller_financing" id="seller_financing" class="form-control" step=".01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.assumable_financing')</label>
                                        <input type="number" min="0" value="{{ $project->finance->assumable_financing }}" name="assumable_financing" id="assumable_financing" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.assumable_finterest')</label>
                                        <input type="text" id="assumable_finterest" name="assumable_finterest" class="form-control" value="{{ $project->finance->assumable_finterest }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.nonCompete')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.seller_ntcomplete')</label>
                                        <input type="text" id="seller_ntcomplete" name="seller_ntcomplete" class="form-control" value="{{ $project->finance->seller_ntcomplete }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.no_seller_nincomplete')</label>
                                        <input type="text" id="no_seller_nincomplete" name="no_seller_nincomplete" class="form-control" value="{{ $project->finance->no_seller_nincomplete }}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.pipleLineCommission')</h3>
                            <hr>
                            <div class="row">    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.commission_rate')</label>
                                        <input type="number" min="0" value="{{ $project->finance->commission_rate }}" name="commission_rate" id="commission_rate" class="form-control" step=".01">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.minimum_commission')</label>
                                        <input type="text" id="minimum_commission" name="minimum_commission" class="form-control" value="{{ $project->finance->minimum_commission }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.fees_retainers')</label>
                                        <input type="text" id="fees_retainers" name="fees_retainers" class="form-control" value="{{ $project->finance->fees_retainers }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.total_commission')</label>
                                        <input type="text" id="total_commission" name="total_commission" class="form-control" value="{{ $project->finance->total_commission }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.my_ncommission')</label>
                                        <input type="text" id="my_ncommission" name="my_ncommission" class="form-control" value="{{ $project->finance->my_ncommission }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.my_ncommission_split')</label>
                                        <input type="text" id="my_ncommission_split" name="my_ncommission_split" class="form-control" value="{{ $project->finance->my_ncommission_split }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.buyable_broker')</label>
                                        <input type="text" id="buyable_broker" name="buyable_broker" class="form-control" value="{{ $project->finance->buyable_broker }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.probability_pipeline')</label>
                                        <input type="text" id="probability_pipeline" name="probability_pipeline" class="form-control" value="{{ $project->finance->probability_pipeline }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.probability_ofclosing')</label>
                                        <input type="text" id="probability_ofclosing" name="probability_ofclosing" class="form-control" value="{{ $project->finance->probability_ofclosing }}">
                                    </div>
                                </div>
                            </div>

                            <h3 class="box-title m-b-10">@lang('modules.projects.soldInformation')</h3>
                            <hr>
                            <div class="row">    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.activated_date')</label>
                                        <input type="text" id="activated_date" name="activated_date" class="form-control" value="{{ $project->finance->activated_date->format($global->date_format) }}">
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.noof_listed_days')</label>
                                        <input type="number" min="0" name="noof_listed_days" id="noof_listed_days" class="form-control" value="{{ $project->finance->noof_listed_days }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.sold_price')</label>
                                        <input type="number" min="0" value="{{ $project->finance->sold_price }}" name="sold_price" id="sold_price" class="form-control" step=".01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.projects.commission')</label>
                                        <input type="text" id="commission" name="commission" class="form-control" value="{{ $project->finance->commission }}">
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <!----------------------------------------------------------------------->
                            
                            

                            <div class="row">
                                @foreach($fields as $field)
                                    <div class="col-md-6">
                                        <label>{{ ucfirst($field->label) }}</label>
                                        <div class="form-group">
                                            @if( $field->type == 'text')
                                                <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'password')
                                                <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">
                                            @elseif($field->type == 'number')
                                                <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$project->custom_fields_data['field_'.$field->id] ?? ''}}">

                                            @elseif($field->type == 'textarea')
                                                <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$project->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                            @elseif($field->type == 'radio')
                                                <div class="radio-list">
                                                    @foreach($field->values as $key=>$value)
                                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                            <div class="radio radio-info">
                                                                <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($project) && $project->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($field->type == 'select')
                                                {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                        $field->values,
                                                         isset($project)?$project->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                 !!}

                                            @elseif($field->type == 'checkbox')
                                            <div class="mt-checkbox-inline custom-checkbox checkbox-{{$field->id}}">
                                                <input type="hidden" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" 
                                                id="{{$field->name.'_'.$field->id}}" value="{{$project->custom_fields_data['field_'.$field->id]}}">
                                                @foreach($field->values as $key => $value)
                                                    <label class="mt-checkbox mt-checkbox-outline">
                                                        <input name="{{$field->name.'_'.$field->id}}[]" class="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                               type="checkbox" value="{{$value}}" onchange="checkboxChange('checkbox-{{$field->id}}', '{{$field->name.'_'.$field->id}}')"
                                                               @if($project->custom_fields_data['field_'.$field->id] != '' && in_array($value ,explode(', ', $project->custom_fields_data['field_'.$field->id]))) checked @endif > {{$value}}
                                                        <span></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @elseif($field->type == 'date')
                                            <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                            value="{{ ($project->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::parse($project->custom_fields_data['field_'.$field->id])->format($global->date_format) : \Carbon\Carbon::now()->format($global->date_format)}}">
                                            @endif
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>

                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                        <div class="form-actions m-t-15">
                            <button type="submit" id="save-form" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>

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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script src="{{ asset('plugins/bower_components/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js') }}"></script>

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

    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}'
    });


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    checkTask();
    getDetails();
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
    function getDetails(){
        const id = {{ $project->client_id }};
        
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

                    /*if(selectedTemplate['client_view_task'] == 'enable'){
                        $("#client_view_task").prop('checked', true);
                        $('#clientNotification').show();
                        $('#readOnly').show();
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
                        $('#readOnly').hide();
                    }
                    if(selectedTemplate['manual_timelog'] == 'enable'){
                        $("#manual_timelog").prop('checked', true);
                    }
                    else{
                        $("#manual_timelog").prop('checked', false);
                    }*/
                }
            }
        })

    }
    @if($project->deadline == null)
        $('#deadlineBox').hide();
    @endif
    $('#without_deadline').click(function () {
        var check = $('#without_deadline').is(":checked") ? true : false;
        if(check == true){
            $('#deadlineBox').hide();
        }
        else{
            $('#deadlineBox').show();
        }
    });

    $("#deadline").datepicker({
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    $("#start_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#deadline').datepicker('setStartDate', minDate);
    });
    $("#lease_expiration").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
    $("#activated_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });


    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.projects.update', [$project->id])}}',
            container: '#updateProject',
            type: "POST",
            redirect: true,
            data: $('#updateProject').serialize()
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

    var completion = $('#completion_percent').val();

    $("#range_01").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: parseInt(completion),
        postfix: "%",
        onFinish: saveRangeData
    });

    var slider = $("#range_01").data("ionRangeSlider");

    $('#calculate-task-progress').change(function () {
        if($(this).is(':checked')){
            slider.update({"disable": true});
        }
        else{
            slider.update({"disable": false});
        }
    })

    function saveRangeData(data) {
        var percent = data.from;
        $('#completion_percent').val(percent);
    }

    $(':reset').on('click', function(evt) {
        evt.preventDefault()
        $form = $(evt.target).closest('form')
        $form[0].reset()
        $form.find('select').select2()
    });

    @if($project->calculate_task_progress == "true")
        slider.update({"disable": true});
    @endif
</script>

<script>
    $('#updateProject').on('click', '#addProjectCategory', function () {
        var url = '{{ route('admin.projectCategory.create-cat')}}';
        $('#modelHeading').html('Manage Project Category');
        $.ajaxModal('#projectCategoryModal', url);
    })
     var AllStates = @json($AllStates);
    
    
    $('#cog_countries_id').change(function (e) {
        // get projects of selected users
        var opts = '';

        var state = AllStates.filter(function (item) {
            return item.cog_countries_id == e.target.value
        });
         
        state.forEach(project => {
            console.log(project);
            opts += `<option value='${project.id}'>${project.name}</option>`
        })

        $('#cog_state_id').html('<option value="">Select State...</option>'+opts)
        $("#cog_state_id").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
    }); 
    
    var AllCities = @json($AllCities);
    
    $('#cog_state_id').change(function (es) {
    //   console.log(AllCities);
        // get projects of selected users
        var options = '';

        var city = AllCities.filter(function (itm) {
            return itm.cog_states_id == es.target.value
        });
        console.log(city); 
        city.forEach(pject => {
            console.log(pject);
            options += `<option value='${pject.id}'>${pject.name}</option>`
        })

        $('#cog_city_id').html('<option value="">Select City...</option>'+options)
        $("#cog_city_id").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
    });
</script>
@endpush
