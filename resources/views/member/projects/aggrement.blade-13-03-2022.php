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

        <section>
            <div class="sttabs tabs-style-line">

                @include('member.projects.show_project_menu')

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
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.franchise_mart') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->franchise_mart ? $project->asset->franchise_mart : 'NA' }}
                                    </span> 
                                </div>
                                
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


                            <h3 class="box-title m-b-10">@lang('modules.projects.assets')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.inventory_value') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->inventory_value ? $project->asset->inventory_value : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.ff_value') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->ff_value ? $project->asset->ff_value : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.accounts_recieveable') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->accounts_recieveable ? $project->asset->accounts_recieveable : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.leashold') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->leashold ? $project->asset->leashold : 'NA' }}
                                    </span> 

                                </div>
                            </div>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.estate_value') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->estate_value ? $project->asset->estate_value : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.other_assets') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->other_assets ? $project->asset->other_assets : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.total_assets') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->total_assets ? $project->asset->total_assets : 'NA' }}
                                    </span>
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.inventory_inlcuded') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->inventory_inlcuded ? $project->asset->inventory_inlcuded : 'NA' }}
                                    </span>
                                </div> 
                            </div>
                            


                            <div class="row project-top-stats">
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.fe_inlcuded') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->fe_inlcuded ? $project->asset->fe_inlcuded : 'NA' }}
                                    </span>
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.accounts_recieveable_include') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->accounts_recieveable_include ? $project->asset->accounts_recieveable_include : 'NA' }}
                                    </span>
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.leashold_include') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->leashold_include ? $project->asset->leashold_include : 'NA' }}
                                    </span>
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.estate_value_available') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->estate_value_available ? $project->asset->estate_value_available : 'NA' }}
                                    </span>
                                </div> 
                            </div>

                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5"> @lang('modules.projects.estate_value_include') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->estate_value_include ? $project->asset->estate_value_include : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.other_assets_inlcuded') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->other_assets_inlcuded ? $project->asset->other_assets_inlcuded : 'NA' }}
                                    </span>
                                </div>

                            </div>


                            <h3 class="box-title m-b-10">@lang('modules.projects.lease')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.type_of_location') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->type_of_location ? $project->asset->type_of_location : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.facilities') : </span>
        
                                    <span class="text-success">
                                        {{ $project->asset->facilities ? $project->asset->facilities : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.monthly_rent') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->monthly_rent ? $project->asset->monthly_rent : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.square_units') : </span>
                                   <span class="text-success">
                                        {{ $project->asset->square_units ? $project->asset->square_units : 'NA' }}
                                    </span> 

                                </div>
                            </div>  
                            
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.lease_expiration') : </span>
                                    <span class="text-success">
                                       {{ $project->asset->lease_expiration->format($global->date_format) ? $project->asset->lease_expiration->format($global->date_format) : 'NA' }}
                                    </span> 
                                </div>
                            </div>


                            <h3 class="box-title m-b-10">@lang('modules.projects.primaryFinancials')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.year') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->finance_year ? $project->finance->finance_year : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.date_source') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->date_source ? $project->finance->date_source : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.total_sales') : </span>
                                   <span class="text-success">
                                        {{ $project->finance->total_sales ? $project->finance->total_sales : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.cost_ship') : </span>
                                   <span class="text-success">
                                        {{ $project->finance->cost_ship ? $project->finance->cost_ship : 'NA' }}
                                    </span> 

                                </div>
                            </div>  
                            
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.total_expenses') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->total_expenses ? $project->finance->total_expenses : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.owner_salary') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->owner_salary ? $project->finance->owner_salary : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.beneficial_addblocks') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->beneficial_addblocks ? $project->finance->beneficial_addblocks : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">Interest : </span>
                                    <span class="text-success">
                                       {{ $project->finance->interest ? $project->finance->interest : 'NA' }}
                                    </span> 
                                </div>
                                
                                
                            </div>

                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">Depreciation : </span>
                                    <span class="text-success">
                                       {{ $project->finance->depreciation ? $project->finance->depreciation : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">Other : </span>
                                    <span class="text-success">
                                       {{ $project->finance->other ? $project->finance->other : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">Seller Depreciation Earnings : </span>
                                    <span class="text-success">
                                       {{ $project->finance->seller_earnings ? $project->finance->seller_earnings : 'NA' }}
                                    </span> 
                                </div>
                            </div>

                            <h3 class="box-title m-b-10">@lang('modules.projects.financing')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.owner_financing') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->owner_financing ? $project->finance->owner_financing : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.owner_financing_interest') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->owner_financing_interest ? $project->finance->owner_financing_interest : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.ownership_months') : </span>
                                   <span class="text-success">
                                        {{ $project->finance->ownership_months ? $project->finance->ownership_months : 'NA' }}
                                    </span> 
                                    
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">

                                   <span class="font-12 text-muted m-l-5">@lang('modules.projects.ownership_monthly_pay') : </span>
                                   <span class="text-success">
                                        {{ $project->finance->ownership_monthly_pay ? $project->finance->ownership_monthly_pay : 'NA' }}
                                    </span> 

                                </div>
                            </div>  
                            
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.seller_financing') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->seller_financing ? $project->finance->seller_financing : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.assumable_financing') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->assumable_financing ? $project->finance->assumable_financing : 'NA' }}
                                    </span> 
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.assumable_finterest') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->assumable_finterest ? $project->finance->assumable_finterest : 'NA' }}
                                    </span> 
                                </div>
                            </div>

                            <h3 class="box-title m-b-10">@lang('modules.projects.nonCompete')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.seller_ntcomplete') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->seller_ntcomplete ? $project->finance->seller_ntcomplete : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.no_seller_nincomplete') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->no_seller_nincomplete ? $project->finance->no_seller_nincomplete : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                
                            </div>  

                            <h3 class="box-title m-b-10">@lang('modules.projects.pipleLineCommission')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.commission_rate') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->commission_rate ? $project->finance->commission_rate : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.minimum_commission') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->minimum_commission ? $project->finance->minimum_commission : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.fees_retainers') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->fees_retainers ? $project->finance->fees_retainers : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.total_commission') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->total_commission ? $project->finance->total_commission : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                
                            </div>  

                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.my_ncommission_split') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->my_ncommission_split ? $project->finance->my_ncommission_split : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.buyable_broker') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->buyable_broker ? $project->finance->buyable_broker : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.probability_pipeline') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->probability_pipeline ? $project->finance->probability_pipeline : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.probability_ofclosing') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->probability_ofclosing ? $project->finance->probability_ofclosing : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                
                            </div> 


                            <h3 class="box-title m-b-10">@lang('modules.projects.soldInformation')</h3>
                            <hr>
                            <div class="row project-top-stats">
                                <div class="col-md-3 m-b-20 m-t-10 text-center">
                                    <span class="font-12 text-muted m-l-5">@lang('modules.projects.activated_date') : </span>
                                    <span class="text-success">
                                       {{ $project->finance->activated_date->format($global->date_format) ? $project->finance->activated_date->format($global->date_format) : 'NA' }}
                                    </span> 
                                </div>
                                
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.noof_listed_days') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->noof_listed_days ? $project->finance->noof_listed_days : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.sold_price') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->sold_price ? $project->finance->sold_price : 'NA' }}
                                    </span>
                                    
                                    
                                </div>
                                <div class="col-md-3 m-b-20 m-t-10 text-center b-l">
                                     <span class="font-12 text-muted m-l-5">@lang('modules.projects.commission') : </span>
        
                                    <span class="text-success">
                                        {{ $project->finance->commission ? $project->finance->commission : 'NA' }}
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
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
