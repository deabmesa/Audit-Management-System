@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card"><div class="card-body"><h6>Total audits</h6><h2>{{ $summary['total_audits'] }}</h2></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h6>Open findings</h6><h2>{{ $summary['open_findings'] }}</h2></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h6>Overdue findings</h6><h2>{{ $summary['overdue_findings'] }}</h2></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h6>Completed audits</h6><h2>{{ $summary['completed_audits'] }}</h2></div></div></div>
    </div>
    <div class="card">
        <div class="card-header">Findings Status</div>
        <div class="card-body"><canvas id="findingsChart"></canvas></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('findingsChart'), {
    type: 'bar',
    data: {
        labels: @json($chartData['labels']),
        datasets: [{ label: 'Findings', data: @json($chartData['values']), backgroundColor: ['#ffc107','#0d6efd','#198754'] }]
    }
});
</script>
@endsection
