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
                        <td>{{ $offer->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td>
                            <x-image-preview :path="$offer->image" folder="special_offer" class="rounded shadow w-25" />
                        </td>
                    </tr>
                    <tr>                        <th>Start Date</th>
                        <td>{{ $offer->start_date ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $offer->end_date ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $offer->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($offer->status ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>
                            {{ $offer->created_at ? $offer->created_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>
                            {{ $offer->updated_at ? $offer->updated_at->format('d M Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
