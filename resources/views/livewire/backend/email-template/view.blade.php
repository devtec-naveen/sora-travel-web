<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered table-hover">
            <colgroup>
                <col width="25%">
                <col width="75%">
            </colgroup>
            <tbody>
                <tr>
                    <th style="vertical-align:top">Title</th>
                    <td>{{ $template->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th style="vertical-align:top">Subject</th>
                    <td>{{ $template->subject ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th style="vertical-align:top">Body</th>
                    <td>{!! $template->body ?? 'N/A' !!}</td>
                </tr>
                <tr>
                    <th style="vertical-align:top">Status</th>
                    <td>
                        @if ($template->status ?? null)
                            <span class="badge {{ $template->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($template->status) }}
                            </span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="vertical-align:top">Created At</th>
                    <td>{{ $template->created_at ? $template->created_at->format('d M, Y H:i') : 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
