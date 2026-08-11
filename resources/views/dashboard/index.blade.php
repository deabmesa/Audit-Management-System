@extends('layouts.app')
@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Total Audits</h6><h3>{{ $totalAudits }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Open Findings</h6><h3>{{ $openFindings }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Open Follow-ups</h6><h3>{{ $followUpProgress['open'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Closed Follow-ups</h6><h3>{{ $followUpProgress['closed'] }}</h3></div></div></div>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h5 class="mb-1">Daily Attendance</h5>
            <p class="text-muted mb-0">
                Current status:
                <span class="badge {{ $isCheckedIn ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $isCheckedIn ? 'Checked in' : 'Checked out' }}</span>
            </p>
            @if($lastAttendanceAt)
                <small class="text-muted">Last activity at: {{ $lastAttendanceAt }}</small>
            @endif
        </div>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('attendance.checkin') }}">@csrf
                <button class="btn btn-success" {{ $isCheckedIn ? 'disabled' : '' }}>Check In</button>
            </form>
            <form method="POST" action="{{ route('attendance.checkout') }}">@csrf
                <button class="btn btn-outline-danger" {{ $isCheckedIn ? '' : 'disabled' }}>Check Out</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Risk Distribution</h5>
        <ul>
            <li>High: {{ $riskDistribution['High'] ?? 0 }}</li>
            <li>Medium: {{ $riskDistribution['Medium'] ?? 0 }}</li>
            <li>Low: {{ $riskDistribution['Low'] ?? 0 }}</li>
        </ul>
    </div>
</div>
@endsection
