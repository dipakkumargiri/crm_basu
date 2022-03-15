@forelse($ClientDocs as $key=>$clientDoc)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ ucwords($clientDoc->name) }}</td>
        <td>
            <a href="{{ route('admin.client-docs.download', $clientDoc->id) }}"
               data-toggle="tooltip" data-original-title="Download"
               class="btn btn-default btn-circle"><i
                        class="fa fa-download"></i></a>
           
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3">@lang('messages.noDocsFound')</td>
    </tr>
@endforelse
