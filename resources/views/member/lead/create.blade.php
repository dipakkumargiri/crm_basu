@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.leads.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.lead.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createLead','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <h3 class="box-title">@lang('modules.lead.companyDetails')</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.lead.companyName')</label>
                                            <input type="text" id="company_name" name="company_name" class="form-control" >
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('modules.lead.website')</label>
                                            <input type="text" id="website" name="website" class="form-control" >
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.address')</label>
                                            <textarea name="address"  id="address"  rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <!--/span-->

                                </div>
                                <!--/row-->
                                <div class="row">
                                    
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label>@lang('modules.clients.officePhoneNumber')</label>
                                                <input type="text" name="office_phone" id="office_phone"   class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                                <input type="text" name="postal_code" id="postalCode"class="form-control">
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
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('app.industry')</label>
                                            <select class="select2 form-control client-category" data-placeholder="@lang('app.industry')"  id="industry_id" name="industry_id">
                                            <option value="">@lang('messages.pleaseSelectIndustry')</option>
                                            @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                              @empty
                                            <option value="">@lang('messages.noIndustryAdded')</option>
                                             @endforelse
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">@lang('app.subIndustry')</label>
                                            <select class="selectpicker form-control select-category" data-placeholder="@lang('modules.clients.clientSubCategory')"  id="sub_industry_id" name="sub_industry_id">                                                 
                                            <option value="">@lang('messages.noSubCategoryAdded')</option> 
                                            @forelse($subcategories as $subCategory)
                                            <option value="{{ $subCategory->id }}">{{ ucwords($subCategory->category_name) }}</option>
                                        @empty
                                            <option value="">@lang('messages.noSubIndustryAdded')</option>
                                        @endforelse  
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                <h3 class="box-title m-t-40">@lang('modules.lead.leadDetails')</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">@lang('modules.tickets.chooseAgents')</label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')" name="agent_id">
                                                <option value="">@lang('modules.tickets.chooseAgents')</option>
                                                @foreach($leadAgents as $emp)
                                                    <option value="{{ $emp->id }}">{{ ucwords($emp->user->name). ' ['.$emp->user->email.']' }} @if($emp->user->id == $user->id)
                                                            (YOU) @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>@lang('app.currency')</label>
                                            <select class="form-control" name="currency_id" id="currency_id">
                                                <option value="">@lang('app.selectCurrency')</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}" @if($currency->id == $currency_id)selected @endif >{{ $currency->currency_symbol.' ('.$currency->currency_code.')' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('app.lead') @lang('app.value')</label>
                                            <input type="number" min="0" value="0" name="value" id="value"  class="form-control">
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('modules.lead.clientName')</label>
                                            <input type="text" name="name" id="name"  class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('modules.lead.clientEmail')</label>
                                            <input type="email" name="email" id="email"  class="form-control">
                                            <span class="help-block">@lang('modules.lead.emailNote')</span>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">
                                    <!--/span-->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('modules.lead.mobile')</label>
                                            <input type="tel" name="mobile" id="mobile" class="form-control">
                                        </div>
                                    </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('app.next_follow_up')</label>
                                                <select name="next_follow_up" id="next_follow_up" class="form-control">
                                                        <option value="yes"> @lang('app.yes')</option>
                                                        <option value="no"> @lang('app.no')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 ">
                                            <div class="form-group" style="margin-top: -2px;">
                                                <label >@lang('modules.lead.leadCategory')
                                                    </label>
                                                    <select class="select2 form-control" name="category_id" id="category_id"
                                                    data-style="form-control">
                                                    @forelse($categories as $category)
                                                    <option value="{{ $category->id }}">{{ ucwords($category->category_name) }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noCategoryAdded')</option>
                                                @endforelse
    
                                                 </select>
                                         </div>
                                   </div>


                                    <!--/span-->
                                </div>
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <div class="form-group">
                                            <label for="">@lang('modules.lead.leadSource') </label>
                                            <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadSource')"  id="source_id" name="source_id">
                                                @foreach($sources as $source)
                                                    <option value="{{ $source->id }}">{{ ucwords($source->type) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                             <div class="form-group">
                                              <label >@lang('modules.lead.leadStage')</label>
                                            <select class="select2 form-control" name="stage_id" id="stage_id"
                                                data-style="form-control">
                                                @forelse($stages as $stage)
                                                <option value="{{ $stage->id }}">{{ ucwords($stage->stage_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noStageAdded')</option>
                                            @endforelse

                                             </select>
                                         </div>
                                    </div>
                                    <div class="col-md-4 ">
                                             <div class="form-group">
                                              <label >@lang('modules.lead.leadType')<!--
                                                <a href="javascript:;" id="addLeadType" class="btn btn-xs btn-success btn-outline"><i class="fa fa-plus"></i></a>-->
                                                 </label>
                                            
                                             <select name="type_id" id="type_id" class="select2 form-control">
                                                <option value="">@lang('app.select')</option>
                                                <option value="1">Buyer</option>
                                                <option value="2">Seller</option>
                                            </select>
                                         </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">
                                    @if(isset($fields))
                                        @foreach($fields as $field)
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label @if($field->required == 'yes') class="required" @endif>{{ ucfirst($field->label) }}</label>
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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>@lang('app.note')</label>
                                        <div class="form-group">
                                            <textarea name="note" id="note" class="form-control summernote" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

<script type="text/javascript">
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

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
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

    $(".date-picker").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('member.leads.store')}}',
            container: '#createLead',
            type: "POST",
            redirect: true,
            data: $('#createLead').serialize()
        })
    });
    $('#sub_industry_id').html("");
    var categories = @json($categories);
    $('#industry_id').change(function (e) {
        var cat_id = $(this).val();
        getCategory(cat_id);
       
    });
    function getCategory(cat_id){
        var url = "{{route('member.clients.getSubcategory')}}";
        var token = "{{ csrf_token() }}";
        $.easyAjax({
        url: url,
        type: "POST",
        data: {'_token': token, cat_id: cat_id},
        success: function (data) {
            var options = [];
            var rData = [];
            rData = data.subcategory;
            $.each(rData, function( index, value ) {
                var selectData = '';
                selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                options.push(selectData);
            });
            $('#sub_industry_id').html(options);
            $('#sub_industry_id').selectpicker('refresh');
        
        }
        })
    }
    $('#cog_countries_id').on('change',function(){
        // alert($(this).val());
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
                $('#city_id').selectpicker('refresh');

            }
            })
        }
</script>
@endpush

