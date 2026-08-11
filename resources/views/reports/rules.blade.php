@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Rules Management</h2>
    <form method="POST" action="{{ route('reports.rules.store') }}">
        @csrf
        <input name="report_template_id" placeholder="Report Template ID" required>
        <select name="role_name">
            <option value="">-- Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>
        <input name="user_id" placeholder="User ID (optional)">
        <select name="can_view"><option value="1">Allow</option><option value="0">Deny</option></select>
        <button class="btn">Save Rule</button>
    </form>

    <table>
        <thead><tr><th>ID</th><th>Report</th><th>Role</th><th>User</th><th>Can View</th></tr></thead>
        <tbody>
        @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->id }}</td>
                <td>{{ $permission->report_template_id }}</td>
                <td>{{ $permission->role_name }}</td>
                <td>{{ $permission->user_id }}</td>
                <td>{{ $permission->can_view ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
