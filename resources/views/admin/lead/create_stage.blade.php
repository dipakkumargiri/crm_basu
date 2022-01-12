<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.lead.leadStage')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table category-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.projectStage.stageName')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($stages as $key=>$type)
                    <tr id="cat-{{ $type->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($type->stage_name) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $type->id }}" class="btn btn-sm btn-danger btn-rounded delete-type">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noProjectStageAdded')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {!! Form::open(['id'=>'createProjectStage','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label class="required">@lang('app.add') @lang('modules.projectStage.stageName')</label>
                        <input type="text" name="stage_name" id="stage_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" id="save-type" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    $('body').on('click', '.delete-type', function() {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.leadStage.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    $('#cat-'+id).fadeOut();
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.stage_name+'</option>';
                        options.push(selectData);
                    });

                    $('#stage_id').html(options);
                    // $('#type_id').select2();
                    $("#stage_id").select2({
                        formatNoMatches: function () {
                            return "{{ __('messages.noRecordFound') }}";
                        }
                    });
                }
            }
        });
    });

    $('#createProjectStage').on('submit', (e) => {
        e.preventDefault();
        $.easyAjax({
            url: '{{route('admin.leadStage.store')}}',
            container: '#createProjectStage',
            type: "POST",
            data: $('#createProjectStage').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    var options = [];
                    var rData = [];
                    let listData = "";
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.stage_name+'</option>';
                        options.push(selectData);
                        listData += '<tr id="cat-' + value.id + '">'+
                            '<td>'+(index+1)+'</td>'+
                            '<td>' + value.stage_name + '</td>'+
                            '<td><a href="javascript:;" data-cat-id="' + value.id + '" class="btn btn-sm btn-danger btn-rounded delete-type">@lang("app.remove")</a></td>'+
                            '</tr>';
                    });
                    $('.category-table tbody' ).html(listData);

                    $('#stage_id').html(options);
                    // $('#type_id').selectpicker('refresh');
                    $('#stage_name').val('');
                }
            }
        })
    });
</script>