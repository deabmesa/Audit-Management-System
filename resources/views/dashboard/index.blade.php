@extends('layouts.app')
@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Total Audits</h6><h3>{{ $totalAudits }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Open Findings</h6><h3>{{ $openFindings }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Open Follow-ups</h6><h3>{{ $followUpProgress['open'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><h6>Closed Follow-ups</h6><h3>{{ $followUpProgress['closed'] }}</h3></div></div></div>
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
