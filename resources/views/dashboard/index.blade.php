@extends('layouts.app')

@section('content')
<h3>Enterprise Dashboard</h3>
<div class="row g-3 mt-2">
    <div class="col-md-4">
        <div class="card"><div class="card-body">
            <h5>Check-In Status</h5>
            <p>Status: <strong>{{ $activeAttendance ? 'Active' : 'Closed' }}</strong></p>
            @if($activeAttendance)
            <form method="POST" action="{{ route('attendance.checkout') }}">@csrf
                <button class="btn btn-danger btn-sm">Check-Out</button>
            </form>
            @endif
        </div></div>
    </div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Audit Statistics</h5>
        <p>Total: {{ $auditStats['total'] }}</p><p>In Progress: {{ $auditStats['in_progress'] }}</p><p>Completed: {{ $auditStats['completed'] }}</p>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5>Findings Summary</h5>
        <p>Open: {{ $findingStats['open'] }}</p><p>In Progress: {{ $findingStats['in_progress'] }}</p><p>Closed: {{ $findingStats['closed'] }}</p>
    </div></div></div>
</div>
@endsection
