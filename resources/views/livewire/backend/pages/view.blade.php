<div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover">
                <colgroup>
                    <col width="25%">
                    <col width="75%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>Page Title</th>
                        <td>{{ $page->page_title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $page->slug ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Title</th>
                        <td>{{ $page->meta_title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Keywords</th>
                        <td>{{ $page->meta_keywords ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Content</th>
                        <td>
                            {!! $page->content ?? 'N/A' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $page->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($page->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>
                            {{ $page->created_at ? $page->created_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>
                            {{ $page->updated_at ? $page->updated_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
