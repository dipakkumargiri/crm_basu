@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i>@lang('app.editBuyer')</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang('app.editBuyer')</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.updatebuyerInfo')
                    [ {{ $clientDetail->name }} ]
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateClient','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">

  
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('app.name')</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $clientDetail->name }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('app.email')</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ $clientDetail->email }}">
                                        <span class="help-block">@lang('modules.client.emailNote')</span>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.employeePassword')</label>
                                        <input type="password" style="display: none">
                                        <input type="password" name="password" id="password" class="form-control" autocomplete="nope">
                                        <span class="help-block"> @lang('modules.client.passwordUpdateNote')</span>
                                    </div>
                                </div>-->
                                <!--/span-->
                            </div>

                            <h3 class="box-title m-t-20">@lang('app.companyDetails')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.client.companyName')</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control"  value="{{ $clientDetail->company_name ?? '' }}">
                                    </div>
                                </div>
                                <!--/span-->
                                @php
                                    $website = explode("://",$clientDetail->website);
                                @endphp

                                <div class="col-md-1 ">
                                    <div class="form-group salutation" style="margin-top: 23px">
                                    <select name="hyper_text" id="hyper_text" class="form-control">
                                        <option value="">--</option>
                                        <option value="http://"  @if(isset($website) && $website[0] == 'http' ) selected @endif  >http://</option>
                                        <option value="https://"@if(isset($website) && $website[0] == 'https' ) selected @endif  >https://</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">@lang('modules.client.website')</label>
                                        <input type="text" id="website" name="website" class="form-control" value="{{ $clientWebsite ? $clientWebsite :'' }}" >
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('app.address')</label>
                                        <textarea name="address"  id="address"  rows="5" class="form-control">{{ $clientDetail->address ?? '' }}</textarea>
                                    </div>
                                </div>
                                <!--/span-->

                            </div>
                            <div class="row">
                                    <div class="col-md-3 ">
                                        <label>@lang('app.mobile')</label>
                                        @php $code = explode(" ",$clientDetail->mobile); @endphp
                                        <div class="form-group">
                                           
                                        <select class="select2 phone_country_code form-control" name="phone_code">
                                            <option value="">--</option>
                                            @foreach ($countries as $item)
                                                <option @if ($code[0]!= "" && $item->phonecode == $code[0])
                                                    selected
                                                @endif
                                                value="{{ $item->id }}">+{{ $item->phonecode.' ('.$item->iso.')' }}</option>
                                            @endforeach
                                        </select>
                                        <input type="tel" name="mobile" id="mobile" class="mobile" autocomplete="nope" value="@if(isset($code[1])){{  $code[1] }}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">@lang('modules.clients.country')</label>
                                            <select class="select2 form-control" name="cog_countries_id" id="cog_countries_id"
                                                data-style="form-control">
                                                    @forelse($Allcountries as $coun)
                                                    <option value="{{ $coun->id }}">{{ ucwords($coun->name) }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.state')</label>
                                            <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.stripeCustomerAddress.state')"  id="state_id" name="state_id">                                                 
                                              
                                                </select>
                                             </div>
                                    </div>
                                
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.city')</label>
                                            <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.stripeCustomerAddress.city')"  id="city_id" name="city_id">                                                 
                                              
                                                </select>
                                            
                                            </div>
                                        </div>      

                                    </div>
                                  
                                <div class="row">
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.clients.officePhoneNumber')</label>
                                            <input type="text" name="office_phone" id="office_phone" value="{{ $clientDetail->office_phone }}"  class="form-control">
                                        </div>
                                    </div>
                                <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                            <input type="text" name="postal_code" id="postalCode"  value="{{ $clientDetail->postal_code }}"  class="form-control">
                                        </div>
                                 </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-bottom: -3px;">
                                        <label for="">@lang('app.industry')
                                             <a href="javascript:;" id="addClientCategory" class="text-info">
                                             <i class="ti-settings text-info"></i> </a>
                                        </label>
                                        <select class="select2 form-control" data-placeholder="@lang('modules.clients.clientCategory')"  id="category_id" name="category_id">
                                         @forelse($categories as $category)
                                         <option @if( $category->id == $clientDetail->category_id) selected @endif value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                          @empty
                                         <option value="">@lang('messages.noCategoryAdded')</option>
                                          @endforelse                
                                         </select>
                                     </div>
                                </div> 
                            <div class="col-md-3">
                                 <div class="form-group">
                                     <label for="">@lang('app.subIndustry')
                                     <a href="javascript:;" id="addClientSubCategory" class="text-info">
                                     <i class="ti-settings text-info"></i></a>
                                    </label>
                                   
                                      <select class="select2 form-control" data-placeholder="@lang('modules.client.clientSubCategory')"  id="sub_category_id" name="sub_category_id">
                                      @forelse($subcategories as $subcategory)
                                     
                                       <option @if( $subcategory->id == $clientDetail->sub_category_id) selected @endif value="{{ $subcategory->id }}">{{ ucwords($subcategory->category_name) }}</option>
                                         @empty
                                         <option value="">@lang('messages.noCategoryAdded')</option>
                                        @endforelse                            
                                     </select>
                                   </div>
                              </div>
                                </div>

                            <!--/row-->
                           <!-- <h3 class="box-title m-t-20">@lang('modules.client.clientOtherDetails')</h3>
                            <hr>-->

                            <!--/row-->

                           <!-- <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Skype</label>
                                        <input type="text" name="skype" id="skype" class="form-control" value="{{ $clientDetail->skype ?? '' }}">
                                    </div>
                                </div>
                                <!--/span

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Linkedin</label>
                                        <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{ $clientDetail->linkedin ?? '' }}">
                                    </div>
                                </div>
                                <!--/span

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Twitter</label>
                                        <input type="text" name="twitter" id="twitter" class="form-control" value="{{ $clientDetail->twitter ?? '' }}">
                                    </div>
                                </div>
                                <!--/span

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="text" name="facebook" id="facebook" class="form-control" value="{{ $clientDetail->facebook ?? '' }}">
                                    </div>
                                </div>
                                <!--/span
                            </div>-->
                            <!--/row-->
                            <!--row gst number-->
                               <!--<div class="row">
                             <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gst_number">@lang('app.gstNumber')</label>
                                        <input type="text" id="gst_number" name="gst_number" class="form-control" value="{{ $clientDetail->gst_number ?? '' }}">
                                    </div>
                                </div>-->
                                
                                <!--/span

                             
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="m-b-10">
                                            <label class="control-label">@lang('modules.emailSettings.emailNotifications')</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" 
                                            @if ($clientDetail->email_notifications)
                                                checked
                                            @endif
                                            name="email_notifications" id="email_notifications1" value="1">
                                            <label for="email_notifications1" class="">
                                                @lang('app.enable') </label>
    
                                        </div>
                                        <div class="radio radio-inline ">
                                            <input type="radio" name="email_notifications"
                                            @if (!$clientDetail->email_notifications)
                                                checked
                                            @endif
    
                                                   id="email_notifications2" value="0">
                                            <label for="email_notifications2" class="">
                                                @lang('app.disable') </label>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>-->
                            <!--/row-->
                            <h3 class="box-title ">@lang('app.leadInformation')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label>@lang('app.leadSource')</label>
                                      <input type="text" name="lead_source" class="form-control" value="{!!  (!empty($sourceDetails)) ? ucwords($sourceDetails->type) : '-' !!}">
                                   
                                        <!-- <label>@lang('app.assignTo')</label>
                                       <a href="javascript:;"  id="addLeadAgent" class="btn btn-xs btn-outline btn-success"><i
                                                            class="fa fa-plus"></i> @lang('app.add') @lang('app.leadAgent')</a></label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" id="agent_id" name="agent_id">
                                                <option value="">@lang('modules.tickets.chooseAgents')</option>
                                                @foreach($leadAgents as $emp)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }} @if($emp->user->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                                            </select>-->
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.changeStatus')</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">--Select--</opton>
                                            @forelse($status as $sts)
                                                <option  value="{{ $sts->id }}"> {{ ucfirst($sts->type) }}</option>
                                            @empty

                                            @endforelse
                                       </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.changeStages')</label>
                                        <select class="form-control" name="stages" id="stages">
                                        @forelse($stages as $stage)
                                        <option value="{{ $stage->id }}"
                                           >{{ ucwords($stage->stage_name) }}</option>
                                    @empty
                                        <option value="">@lang('messages.noStageAdded')</option>
                                    @endforelse
                                       </select>
                                    </div>
                                </div>
                               </div> 
                              <!-- <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.leadSource')</label>
                                      <input type="text" name="lead_source" class="form-control" value="{!!  (!empty($sourceDetails)) ? ucwords($sourceDetails->type) : '-' !!}">
                                    </div>
                                </div>
                                </div>-->

                            <div class="row">
                                @if(isset($fields))
                                    @foreach($fields as $field)
                                        <div class="col-md-6">
                                            <label>{{ ucfirst($field->label) }}</label>
                                            <div class="form-group">
                                                @if( $field->type == 'text')
                                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'password')
                                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                @elseif($field->type == 'number')
                                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                @elseif($field->type == 'textarea')
                                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$clientDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                @elseif($field->type == 'radio')
                                                    <div class="radio-list">
                                                        @foreach($field->values as $key=>$value)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($clientDetail) && $clientDetail->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type == 'select')
                                                    {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                            $field->values,
                                                             isset($clientDetail)?$clientDetail->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                     !!}

                                                @elseif($field->type == 'checkbox')
                                                <div class="mt-checkbox-inline custom-checkbox checkbox-{{$field->id}}">
                                                    <input type="hidden" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" 
                                                    id="{{$field->name.'_'.$field->id}}" value="{{$clientDetail->custom_fields_data['field_'.$field->id]}}">
                                                    @foreach($field->values as $key => $value)
                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                            <input name="{{$field->name.'_'.$field->id}}[]" class="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                   type="checkbox" value="{{$value}}" onchange="checkboxChange('checkbox-{{$field->id}}', '{{$field->name.'_'.$field->id}}')"
                                                                   @if($clientDetail->custom_fields_data['field_'.$field->id] != '' && in_array($value ,explode(', ', $clientDetail->custom_fields_data['field_'.$field->id]))) checked @endif > {{$value}}
                                                            <span></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @elseif($field->type == 'date')
                                                    
                                                    <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                    value="{{ isset($clientDetail->custom_fields_data['field_'.$field->id]) ? \Carbon\Carbon::parse($clientDetail->custom_fields_data['field_'.$field->id])->format($global->date_format) : \Carbon\Carbon::now()->format($global->date_format)}}">
                                                @endif
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <!--<div class="row">
                                <div class="col-xs-12">
                                    <label>@lang('app.shippingAddress')</label>
                                    <div class="form-group">
                                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4">{{$clientDetail->shipping_address ?? ''}}</textarea>
                                    </div>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-xs-12">
                                    <label>@lang('app.note')</label>
                                    <div class="form-group">
                                        <textarea name="note" id="note" class="form-control summernote" rows="5">{{ $clientDetail->note ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-default">@lang('app.back')</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="clientCategoryModal" role="dialog" aria-labelledby="myModalLabel"
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
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script>
    $('#cog_countries_id').val('<?php echo $clientDetail->country_id; ?>');
    $(document).ready(function(){
        $("#cog_countries_id").trigger("click");
});
   
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

    var subCategories = @json($subcategories);
    $('#category_id').change(function (e) {
        // get projects of selected users
        var opts = '';

        var subCategory = subCategories.filter(function (item) {
            return item.category_id == e.target.value
        });
        subCategory.forEach(project => {
            console.log(project);
            opts += `<option value='${project.id}'>${project.category_name}</option>`
        })

        $('#sub_category_id').html('<option value="">Select Sub Category...</option>'+opts)
        $("#sub_category_id").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
    });
    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

     $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.update_buyer', [$clientDetail->id])}}',
            container: '#updateClient',
            type: "PUT",
            redirect: true,
            data: $('#updateClient').serialize()
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
            ["view", ["fullscreen"]]
        ]
    });
    $('#addClientCategory').click(function () {
        var url = '{{ route('admin.clientCategory.create')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#clientCategoryModal', url);
    })
    $('#addClientSubCategory').click(function () {
        var url = '{{ route('admin.clientSubCategory.create')}}';
        $('#modelHeading').html('...');
        $.ajaxModal('#clientCategoryModal', url);
    })
$('#status').val('<?php echo $statusDetails->type ;?>');
$('#cog_countries_id').on('click',function(){
     //   alert($(this).val());
        var country_id = $(this).val();
        getState(country_id);
    })
    function getState(country_id){
            var url = "{{route('member.leads.getState')}}";
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
                $('#state_id').val('<?php echo $clientDetail->state; ?>');
                var state_id='<?php echo $clientDetail->state; ?>';
               if(state_id!=''){
                $("#state_id").trigger("change");
               }
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
                var url = "{{route('member.leads.getCity')}}";
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
                    $('#city_id').val('<?php echo $clientDetail->cit; ?>');
                    $('#city_id').selectpicker('refresh');
    
                }
            })
            } 

            $('#createLead').on('click', '#addLeadAgent', function () {
        var url = '{{ route('admin.lead-agent-settings.create')}}';
        $('#modelHeading').html('Manage Lead Agent');
        $.ajaxModal('#projectCategoryModal', url);
    })
    $('#status').val('<?php echo $clientDetail->lead_status; ?>');
    $('#stages').val('<?php echo $clientDetail->lead_stage; ?>');
    $('#agent_id').val('<?php echo $clientDetail->agent_id; ?>');
</script>
@endpush
