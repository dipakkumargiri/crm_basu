@extends('layouts.app')
@section('page-title')
<div class="row bg-title">
    <!-- .page title -->
    <div class="col-lg-7 col-md-4 col-sm-4 col-xs-12 bg-title-left">
        <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang('app.project') #{{ $project->id }} - {{ ucwords($project->project_name) }}</h4>
    </div>
    <!-- /.page title -->
    <!-- .breadcrumb -->
    <div class="col-lg-5 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
        @php $projectPin = $project->pinned(); @endphp
        <a href="javascript:;" class="btn btn-sm btn-info @if(!$projectPin) btn-outline @endif"  data-placement="bottom"  data-toggle="tooltip" data-original-title="@if($projectPin) @lang('app.unpin') @else @lang('app.pin') @endif"   data-pinned="@if($projectPin) pinned @else unpinned @endif" id="pinnedItem" >
            <i class="icon-pin icon-2 pin-icon  @if($projectPin) pinned @else unpinned @endif" ></i>
        </a>

        <a href="{{ route('admin.payments.create', ['project' => $project->id]) }}" class="btn btn-sm btn-primary btn-outline" ><i class="fa fa-plus"></i> @lang('modules.payments.addPayment')</a>

        @php
            if ($project->status == 'in progress') {
                $statusText = __('app.inProgress');
                $statusTextColor = 'text-info';
                $btnTextColor = 'btn-info';
            } else if ($project->status == 'on hold') {
                $statusText = __('app.onHold');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            } else if ($project->status == 'not started') {
                $statusText = __('app.notStarted');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            } else if ($project->status == 'canceled') {
                $statusText = __('app.canceled');
                $statusTextColor = 'text-danger';
                $btnTextColor = 'btn-danger';
            } else if ($project->status == 'finished') {
                $statusText = __('app.finished');
                $statusTextColor = 'text-success';
                $btnTextColor = 'btn-success';
            }
            else if ($project->status == 'under review') {
                $statusText = __('app.underReview');
                $statusTextColor = 'text-warning';
                $btnTextColor = 'btn-warning';
            }
        @endphp

        <div class="btn-group dropdown">
            <button aria-expanded="true" data-toggle="dropdown"
                    class="btn b-all dropdown-toggle waves-effect waves-light visible-lg visible-md"
                    type="button">{{ $statusText }} <span style="width: 15px; height: 15px;"
                    class="btn {{ $btnTextColor }} btn-small btn-circle">&nbsp;</span></button>
            <ul role="menu" class="dropdown-menu pull-right">
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="in progress">@lang('app.inProgress')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-info btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="on hold">@lang('app.onHold')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="not started">@lang('app.notStarted')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-warning btn-small btn-circle">&nbsp;</span>
                    </a>
                </li><i class="icon-pushpin "></i>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="canceled">@lang('app.canceled')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-danger btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="submit-ticket" data-status="finished">@lang('app.finished')
                        <span style="width: 15px; height: 15px;"
                              class="btn btn-success btn-small btn-circle">&nbsp;</span>
                    </a>
                </li>
            </ul>
        </div>

        <!--<a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-success btn-outline" ><i class="icon-note"></i> @lang('app.edit')</a>-->

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
            <li><a href="{{ route('admin.projects.index') }}">{{ __($pageTitle) }}</a></li>
            <li class="active">@lang('app.details')</li>
        </ol>
    </div>
    <!-- /.breadcrumb -->
</div>
@endsection
@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">

<style>
    #section-line-1 .col-in{
        padding:0 10px;
    }

    #section-line-1 .col-in h3{
        font-size: 15px;
    }

    #project-timeline .panel-body {
        max-height: 389px !important;
    }

    #milestones .panel-body {
        max-height: 189px;
        overflow: auto;
    }
    .panel-body{
        overflow-wrap:break-word;
    }
</style>
@endpush
@section('content')

<div class="row">
    <div class="col-xs-12">

        <section>
            <div class="sttabs tabs-style-line">

                @include('admin.projects.show_project_menu')

                <div class="white-box">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.refferedBy') : </span>
                                    <span class="text-success">
                                       {{ $project->aggrement->reffered_by }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.refferedFee') : </span>
        
                                    <span class="text-success">
                                        {{ $project->aggrement->reffered_fee ? $project->aggrement->reffered_fee : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.agreementType') : </span>
                                   <span class="text-success">
                                        {{ $project->aggrement->agreement_type ? $project->aggrement->agreement_type : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.agencyType') : </span>
                                   <span class="text-success">
                                        {{ $project->aggrement->agency_type ? $project->aggrement->agency_type : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.startDate') : </span>
                                    <span class="text-success">
                                       {{ $project->start_date->format($global->date_format) }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.deadline') : </span>
        
                                    <span class="text-success">
                                        @if($project->deadline){{ $project->deadline->format($global->date_format) }}@else {{ \Carbon\Carbon::now()->format($global->date_format) }} @endif
                                    </span>
                                    
                                    
                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessDetail')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.represented') : </span>
                                    <span class="text-success">
                                       {{ $project->business->represented ? $project->business->represented : 'NA'}}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.lead.companyName') : </span>
        
                                    <span class="text-success">
                                        {{ $project->lead->company_name ? $project->lead->company_name : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.lead.website') : </span>
                                   <span class="text-success">
                                        {{ $project->lead->website ? $project->lead->website : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.lead.mobile') : </span>
                                   <span class="text-success">
                                        {{ $project->lead->mobile ? $project->lead->mobile : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.established') : </span>
                                    <span class="text-success">
                                       {{ $project->business->established ? $project->business->established : 'NA'}}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.owned') : </span>
        
                                    <span class="text-success">
                                        {{ $project->business->owned ? $project->business->owned : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.fromOwnership') : </span>
                                   <span class="text-success">
                                        {{ $project->business->from_ownership  ? $project->business->from_ownership  : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.note') : </span>
                                   <span class="text-success">
                                        {!! $project->business->comments ? $project->business->comments : 'NA' !!}
                                    </span> 

                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessSale')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.projectBudget') : </span>
                                    <span class="text-success">
                                       {{ $project->project_budget ? $project->currency->currency_symbol.' '.$project->project_budget : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.downPayment') : </span>
        
                                    <span class="text-success">
                                        {{ $project->business->down_payment ? $project->business->down_payment : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.vat_value') : </span>
                                   <span class="text-success">
                                        {{ $project->business->vat_value ? $project->business->vat_value : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.value_tax') : </span>
                                   <span class="text-success">
                                        {{ $project->business->value_tax ? $project->business->value_tax : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.realestate_price') : </span>
                                    <span class="text-success">
                                       {{ $project->business->realestate_price ? $project->currency->currency_symbol.' '.$project->business->realestate_price : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.training_support') : </span>
        
                                    <span class="text-success">
                                        {{ $project->business->training_support ? $project->business->training_support : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.nooftraining_week') : </span>
                                   <span class="text-success">
                                        {{ $project->business->nooftraining_week ? $project->business->nooftraining_week : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.sale_reason') : </span>
                                   <span class="text-success">
                                        {{ $project->business->sale_reason ? $project->business->sale_reason : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.businessWebsite')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.ad_headline') : </span>
                                    <span class="text-success">
                                       {{ $project->detail->ad_headline ? $project->detail->ad_headline : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.listing_url') : </span>
        
                                    <span class="text-success">
                                        {{ $project->detail->listing_url ? $project->detail->listing_url : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.office_location') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->office_location ? $project->detail->office_location : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.promoted') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->promoted ? $project->detail->promoted : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.agent_promoted') : </span>
                                    <span class="text-success">
                                       {{ $project->detail->agent_promoted ? $project->detail->agent_promoted : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-9 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.projectSummary') : </span>
        
                                    <span class="text-success">
                                        {!! $project->detail->projectSummary ? $project->detail->projectSummary : 'NA' !!}
                                    </span>
                                    
                                    
                                </div>
                                
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.commentsInstructions')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.business_history') : </span>
                                    <span class="text-success">
                                       {{ $project->detail->business_history ? $project->detail->business_history : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.competitive_overview') : </span>
        
                                    <span class="text-success">
                                        {{ $project->detail->competitive_overview ? $project->detail->competitive_overview : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.potential_growth') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->potential_growth ? $project->detail->potential_growth : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.showing_instruction') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->showing_instruction ? $project->detail->showing_instruction : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.geogrpahicalLocation')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.general_location') : </span>
                                    <span class="text-success">
                                       {{ $project->detail->general_location ? $project->detail->general_location : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.gmap_url') : </span>
        
                                    <span class="text-success">
                                        {{ $project->detail->gmap_url ? $project->detail->gmap_url : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.address') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->address ? $project->detail->address : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.post_code') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->post_code ? $project->detail->post_code : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.stripeCustomerAddress.country') : </span>
                                    <span class="text-success">
                                       {{ $project->detail->country_id ? $project->detail->country_id : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.stripeCustomerAddress.state') : </span>
        
                                    <span class="text-success">
                                        {{ $project->detail->state_id ? $project->detail->state_id : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.stripeCustomerAddress.city') : </span>
                                   <span class="text-success">
                                        {{ $project->detail->city_id ? $project->detail->city_id : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                
                            </div>
                            <h3 class="box-title m-b-10">@lang('modules.projects.generalOperations')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.business_hours') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->business_hours ? $project->asset->business_hours : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.weekly_hours') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->weekly_hours ? $project->asset->weekly_hours : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.relocation') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->relocation ? $project->asset->relocation : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.franchisee_operations') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->franchisee_operations ? $project->asset->franchisee_operations : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.home_based') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->home_based ? $project->asset->home_based : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.no_of_emp') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->no_of_emp ? $project->asset->no_of_emp : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                
                                
                            </div>
                            

                        </div>

                        
                    </div>

                    

                </div>
                <!-- /content -->
            </div>
            <!-- /tabs -->
        </section>
    </div>


</div>
<!-- .row -->

{{--Ajax Modal--}}
<div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modal-data-application">
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
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}

{{--Ajax Modal--}}
<div class="modal fade bs-modal-lg in" id="project-summary-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="icon-layers"></i> @lang('modules.projects.projectSummary')</h4>
            </div>
            <div class="modal-body">
                {!! $project->project_summary !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">@lang('app.close')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->.
</div>
{{--Ajax Modal Ends--}}


@endsection
 @push('footer-script')
 <script src="{{ asset('plugins/bower_components/Chart.js/Chart.min.js') }}"></script>

 <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
 <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>

 <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">

    $("body").tooltip({
        selector: '[data-toggle="tooltip"]', trigger: "hover"
    });
    $(document).ready(function(){
        $('[rel=tooltip]').tooltip({ trigger: "hover" });
    });
    function pieChart(taskStatus) {
        var ctx3 = document.getElementById("chart3").getContext("2d");
        var data3 = new Array();
        $.each(taskStatus, function(key,val){
            // console.log("key : "+key+" ; value : "+val);
            data3.push(
                {
                    value: parseInt(val.count),
                    color: val.color,
                    highlight: "#57ecc8",
                    label: val.label
                }
            );
        });
        // console.log(data3);
        var myPieChart = new Chart(ctx3).Pie(data3,{
            segmentShowStroke : true,
            segmentStrokeColor : "#fff",
            segmentStrokeWidth : 0,
            animationSteps : 100,
            tooltipCornerRadius: 0,
            animationEasing : "easeOutBounce",
            animateRotate : true,
            animateScale : false,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            responsive: true
        });
    }
    $('body').on('click', '#pinnedItem', function(){
            var type = $('#pinnedItem').attr('data-pinned');
            var id = {{ $project->id }};
            console.log(['type', type]);
            var dataPin = type.trim(type);
            if(dataPin == 'pinned'){
                swal({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.confirmation.pinnedProject')",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "@lang('messages.unpinIt')",
                    cancelButtonText: "@lang('messages.confirmNoArchive')",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {

                        var url = "{{ route('admin.pinned.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";
                        var txt = "{{ __('app.pin') }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
//                                    $.unblockUI();
                                    $('.pin-icon').removeClass('pinned');
                                    $('.pin-icon').addClass('unpinned');
                                    $('#pinnedText').html(txt);
                                    $('#pinnedItem').attr('data-pinned','unpinned');
                                    $('#pinnedItem').attr('data-original-title','Pin');
                                    $("#pinnedItem").tooltip("hide");

                                }
                            }
                        });
                    }
                });
            }
        else {

                swal({
                    title: "Are you sure?",
                    text: "You want to pin this project!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, pin it!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('admin.pinned.store') }}";

                        var token = "{{ csrf_token() }}";
                        var txt = "{{ __('app.removePinned') }}";
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token,'project_id':id},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    $('.pin-icon').removeClass('unpinned');
                                    $('.pin-icon').addClass('pinned');
                                    $('#pinnedText').html(txt);
                                    $('#pinnedItem').attr('data-pinned','pinned');
                                    $('#pinnedItem').attr('data-original-title','Unpin');
                                    $("#pinnedItem").tooltip("hide");
                                }
                            }
                        });
                    }
                });

            }
        });
       
    @if(!empty($taskStatus))
        pieChart(jQuery.parseJSON('{!! $taskStatus !!}'));
    @endif

    
</script>

<script type="text/javascript">

    $('#timer-list').on('click', '.stop-timer', function () {
       var id = $(this).data('time-id');
        var url = '{{route('admin.time-logs.stopTimer', ':id')}}';
        url = url.replace(':id', id);
        var token = '{{ csrf_token() }}'
        $.easyAjax({
            url: url,
            type: "POST",
            data: {timeId: id, _token: token},
            success: function (data) {
                $('#timer-list').html(data.html);
            }
        })

    });

    $('.milestone-detail').click(function(){
        var id = $(this).data('milestone-id');
        var url = '{{ route('admin.milestones.detail', ":id")}}';
        url = url.replace(':id', id);
        $('#modelHeading').html('@lang('app.update') @lang('modules.projects.milestones')');
        $.ajaxModal('#projectCategoryModal',url);
    })

    $('.submit-ticket').click(function () {

        const status = $(this).data('status');
        const url = '{{route('admin.projects.updateStatus', $project->id)}}';
        const token = '{{ csrf_token() }}'

        $.easyAjax({
            url: url,
            type: "POST",
            data: {status: status, _token: token},
            success: function (data) {
                window.location.reload();
            }
        })
    });
    $('ul.showProjectTabs .projects').addClass('tab-current');
</script>

@endpush
