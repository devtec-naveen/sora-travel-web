<div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover">
                <colgroup>
                    <col width="20%">
                    <col width="80%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $user->phone_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            {{ $user->role == 1 ? 'User' : ($user->role == 2 ? 'Admin' : 'N/A') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Email Verified</th>
                        <td>
                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at ? $user->updated_at->format('d M Y') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
