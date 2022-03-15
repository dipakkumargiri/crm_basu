<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.projects.projectMarketing')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table category-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.projectMarketing.MarketingStatusName')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($ as $key=>$status)
                    <tr id="cat-{{ $status->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($status->marketing_status) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $status->id }}"
                               class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noMarketingStatus')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {!! Form::open(['id'=>'createprojectMarketing','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('modules.projectMarketing.MarketingStatusName')</label>
                        <input type="text" name="marketing_status" id="marketing_status" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" id="save-category" class="btn btn-success"><i
                        class="fa fa-check"></i> @lang('app.save')
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $('body').on('click', '.delete-category', function(e) {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.projectMarketing.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#cat-' + id).fadeOut();
                    let options = [];
                    let rData = [];
                    rData = response.data;
                    $.each(rData, function (index, value) {
                        var selectData = '';
                        selectData = '<option value="' + value.id + '">' + value.marketing_status + '</option>';
                        options.push(selectData);
                    });

                    $('#category_id').html(options);
                    $('#category_id').selectpicker('refresh');
                }
            }
        });
        e.preventDefault();
    });

    $('#createprojectMarketing').on('submit', (e) => {

        $.easyAjax({
            url: '{{route('admin.projectMarketing.store-cat')}}',
            container: '#createprojectMarketing',
            type: "POST",
            data: $('#createprojectMarketing').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    let options = [];
                    let rData = [];
                    let listData = "";
                    rData = response.data;
                    $.each(rData, function (index, value) {
                        var selectData = '';
                        selectData = '<option value="' + value.id + '">' + value.marketing_status + '</option>';
                        options.push(selectData);
                        listData += '<tr id="cat-' + value.id + '">'+
                        '<td>'+(index+1)+'</td>'+
                        '<td>' + value.marketing_status + '</td>'+
                        '<td><a href="javascript:;" data-cat-id="' + value.id + '" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>'+
                        '</tr>';
                    });

                    $('.category-table tbody' ).html(listData);

                    $('#category_id').html(options);
                    $('#category_id').selectpicker('refresh');
                    $('#marketing_status').val('');
                }
            }
        })
        e.preventDefault();
    });

</script>