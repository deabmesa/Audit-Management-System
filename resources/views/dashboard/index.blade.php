@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-5">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded shadow p-4">Audits: <strong>{{ $auditCount }}</strong></div>
    <div class="bg-white rounded shadow p-4">Open Issues: <strong>{{ $openIssues }}</strong></div>
    <div class="bg-white rounded shadow p-4">Closed Issues: <strong>{{ $closedIssues }}</strong></div>
    <div class="bg-white rounded shadow p-4">Due in 7 Days: <strong>{{ $dueSoon }}</strong></div>
</div>
<div class="bg-white p-6 rounded shadow">
    <h2 class="font-semibold mb-3">Issue Status Chart</h2>
    <div class="h-48 flex items-end gap-6">
        <div class="bg-cyan-600 w-20" style="height: {{ max(10, $openIssues * 20) }}px"></div>
        <div class="bg-green-600 w-20" style="height: {{ max(10, $closedIssues * 20) }}px"></div>
    </div>
</div>
@endsection
