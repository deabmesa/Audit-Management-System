@extends('layouts.app')

@section('content')
    <h1 class="title">Please Select System For Your Task</h1>

    <div class="grid grid-2" style="max-width:900px;margin:0 auto 24px;">
        <div class="card" style="text-align:center;border:2px solid #fb923c;">
            <h2>Staff Information</h2>
            <p>Search staff, profile, department, and branch information from read-only PostgreSQL.</p>
            <a class="btn" href="{{ route('staff.index') }}">Open Staff Information</a>
        </div>
        <div class="card" style="text-align:center;border:2px solid #fb923c;">
            <h2>PAMS</h2>
            <p>Audit Program, Findings, Recommendations, Follow-up, Reports, and Evidence Upload.</p>
            <a class="btn" href="{{ route('audit.index') }}">Open PAMS</a>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h3>Check In / Out</h3>
        <form method="POST" action="{{ route('checkin.store') }}" class="grid grid-2">
            @csrf
            <div>
                <label>Branch</label>
                <select name="real_branch" style="width:100%;padding:10px;border-radius:8px;">
                    @foreach($branches as $branch)
                        <option value="{{ $branch }}">{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Reason</label>
                <input type="text" name="reason" required style="width:100%;padding:10px;border-radius:8px;">
            </div>
            <div><button class="btn" type="submit">Check-In</button></div>
        </form>
    </div>

    <div class="card">
        <h3>Check-In History</h3>
        <table>
            <thead>
            <tr><th>No</th><th>ID</th><th>IP</th><th>IP BR</th><th>Real BR</th><th>Check-In</th><th>Check-Out</th><th>Reason</th></tr>
            </thead>
            <tbody>
            @foreach($history as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->user_id }}</td>
                    <td>{{ $row->ip_address }}</td>
                    <td>{{ $row->ip_branch }}</td>
                    <td>{{ $row->real_branch }}</td>
                    <td>{{ $row->checked_in_at }}</td>
                    <td>{{ $row->checked_out_at }}</td>
                    <td>{{ $row->reason }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $history->links() }}
    </div>
@endsection
