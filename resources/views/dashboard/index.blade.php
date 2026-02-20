@extends('layouts.app')

@section('content')
<h1>Dashboard</h1>
<ul>
    <li>Total audits: {{ $totalAudits }}</li>
    <li>Open issues: {{ $openIssues }}</li>
    <li>Overdue issues: {{ $overdueIssues }}</li>
</ul>
<canvas id="auditStatusChart" height="80"></canvas>
<script>
new Chart(document.getElementById('auditStatusChart'), {
    type: 'bar',
    data: {
        labels: @json($statusByAudit->keys()),
        datasets: [{ label: 'Audits by Status', data: @json($statusByAudit->values()) }]
    }
});
</script>
@endsection
