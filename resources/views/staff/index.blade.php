@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Staff Information</h2>
        <form method="GET" style="margin-bottom:12px;">
            <input name="keyword" value="{{ $keyword }}" placeholder="Search staff" style="padding:10px;border-radius:8px;">
            <button class="btn">Search</button>
        </form>
        <table>
            <thead><tr><th>Staff ID</th><th>Name</th><th>Department</th><th>Branch</th><th>Email</th></tr></thead>
            <tbody>
            @foreach($staff as $member)
                <tr>
                    <td>{{ $member->staff_id }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->department_name }}</td>
                    <td>{{ $member->branch_name }}</td>
                    <td>{{ $member->email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $staff->links() }}
    </div>
@endsection
