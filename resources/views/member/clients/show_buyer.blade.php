@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>@lang('app.buyer')</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang('app.buyer')</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">

            <!--<a href="{{ route('admin.clients.edit',$clientDetail->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>-->
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')


    <div class="row">


        @include('member.clients.client_header')

        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('member.clients.tabs_buyer')


                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-xs-12">
                                    <div class="white-box">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.employees.fullName')</strong> <br>
                                                <p class="text-muted">{{ ucwords($clientDetail->name) }}</p>

                                            </div>
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('app.email')</strong> <br>
                                                <p class="text-muted">{{ $clientDetail->email }}</p>
                                            </div>
                                            <div class="col-md-4 col-xs-6"> <strong>Phone Number</strong> <br>
                                                <p class="text-muted">{{ $clientDetail->mobile ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>Organization</strong> <br>
                                                <p class="text-muted">{{ (!empty($clientDetail) ) ? ucwords($clientDetail->company_name) : '-'}}</p>
                                            </div>
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.client.website')</strong> <br>
                                                <p class="text-muted">{{ $clientDetail->website ?? '-' }}</p>
                                            </div>
                                            <div class="col-xs-4 b-r"> <strong>Location</strong> <br>
                                                <p class="text-muted">{!!  (!empty($clientDetail)) ? ucwords($clientDetail->address) : '-' !!}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-4 col-xs-6 b-r"> <strong>Stage</strong> <br>
                                                <p class="text-muted">{{ (!empty($stageDetails) ) ? ucwords($stageDetails->stage_name) : '-'}}</p>
                                            </div>
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>Status</strong> <br>
                                                <p class="text-muted">{{ $statusDetails->type ?? '-' }}</p>
                                            </div>
                                            <div class="col-xs-4 b-r"> <strong>Lead Source</strong> <br>
                                                <p class="text-muted">{!!  (!empty($sourceDetails)) ? ucwords($sourceDetails->type) : '-' !!}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-4 col-xs-6 b-r"> <strong>Assigned To</strong> <br>
                                                <p class="text-muted">{{ (!empty($againtDetails) ) ? ucwords($againtDetails->name) : '-'}}</p>
                                            </div>
                                           
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.clients.clientCategory')</strong> <br>
                                                <p class="text-muted">@if($clientDetail->clientCategory){{ $clientDetail->clientCategory->category_name }}@endif</p>
                                                </div>
                                                <!--<div class="col-md-4 col-xs-6 b-r"> <strong>Last Updated</strong> <br>
                                                <p class="text-muted">{{ $leadDetails->updated_at ?? '-' }}</p>
                                            </div>-->
                                            <div class="col-md-4 col-xs-6 b-r"> <strong>@lang('modules.clients.clientSubCategory')</strong> <br>
                                                    <p class="text-muted">@if($clientDetail->clientCategory){{ $clientDetail->clientSubcategory->category_name }}@endif</p>
                                                </div>
                                        </div>
                                     
                                       <!-- <div class="row">
                                            @if($clientDetail->category_id != null)
                                                <div class="col-xs-6 b-r"> <strong>@lang('modules.clients.clientCategory')</strong> <br>
                                                <p class="text-muted">{{ $clientDetail->clientCategory->category_name }}</p>
                                                </div>
                                             @endif
                                            @if($clientDetail->sub_category_id != null)
                                                <div class="col-xs-6"> <strong>@lang('modules.clients.clientSubCategory')</strong> <br>
                                                    <p class="text-muted">{{ $clientDetail->clientSubcategory->category_name }}</p>
                                                </div>
                                            @endif
                                           
                                        </div>-->

                                        {{--Custom fields data--}}
                                        @if(isset($fields))
                                            <div class="row">
                                                <hr>
                                                @foreach($fields as $field)
                                                    <div class="col-md-4">
                                                        <strong>{{ ucfirst($field->label) }}</strong> <br>
                                                        <p class="text-muted">
                                                            @if( $field->type == 'text')
                                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                                            @elseif($field->type == 'password')
                                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}
                                                            @elseif($field->type == 'number')
                                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                                            @elseif($field->type == 'textarea')
                                                                {{$clientDetail->custom_fields_data['field_'.$field->id] ?? '-'}}

                                                            @elseif($field->type == 'radio')
                                                                {{ !is_null($clientDetail->custom_fields_data['field_'.$field->id]) ? $clientDetail->custom_fields_data['field_'.$field->id] : '-' }}
                                                            @elseif($field->type == 'select')
                                                                {{ (!is_null($clientDetail->custom_fields_data['field_'.$field->id]) && $clientDetail->custom_fields_data['field_'.$field->id] != '') ? $field->values[$clientDetail->custom_fields_data['field_'.$field->id]] : '-' }}
                                                            @elseif($field->type == 'checkbox')
                                                            <ul>
                                                                @foreach($field->values as $key => $value)
                                                                    @if($clientDetail->custom_fields_data['field_'.$field->id] != '' && in_array($value ,explode(', ', $clientDetail->custom_fields_data['field_'.$field->id]))) <li>{{$value}}</li> @endif
                                                                @endforeach
                                                            </ul>
                                                            @elseif($field->type == 'date')
                                                                {{ \Carbon\Carbon::parse($clientDetail->custom_fields_data['field_'.$field->id])->format($global->date_format)}}
                                                            @endif
                                                        </p>

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{--custom fields data end--}}

                                        <div class="row">
                                            <div class="col-xs-12"> <strong>@lang('app.note')</strong> <br>
                                                <p class="text-muted">{!!  $clientDetail->note !!}</p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </section>
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script>
        $('ul.showClientTabs .clientProfile').addClass('tab-current');
    </script>
@endpush
