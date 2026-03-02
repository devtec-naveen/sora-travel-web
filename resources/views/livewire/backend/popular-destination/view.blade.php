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
                        <th>Title</th>
                        <td>{{ $destination->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $destination->slug ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td>
                            <x-image-preview :path="$destination->image" folder="popular_destination" class="rounded shadow" />
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $destination->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($destination->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>
                            {{ $destination->created_at ? $destination->created_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>
                            {{ $destination->updated_at ? $destination->updated_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
