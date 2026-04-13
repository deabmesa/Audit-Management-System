@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between">
<h4>{{ $finding->title }}</h4>
<div class="d-flex gap-2">
    <a href="{{ route('reports.finding.pdf', $finding) }}" class="btn btn-outline-secondary">PDF</a>
    <a href="{{ route('findings.timeline', $finding) }}" class="btn btn-outline-dark">Timeline</a>
    <a href="{{ route('evidence.index', $finding) }}" class="btn btn-outline-primary">Evidence</a>
</div>
</div>
<div class="card mt-3"><div class="card-body">
<p>{{ $finding->description }}</p>
<p><strong>Severity:</strong> {{ $finding->severity }}</p>
<p><strong>Status:</strong> {{ $finding->status }}</p>
</div></div>
<div class="mt-3 d-flex gap-2">
<form method="POST" action="{{ route('findings.approve', $finding) }}">@csrf <button class="btn btn-success">Approve</button></form>
<form method="POST" action="{{ route('findings.reject', $finding) }}">@csrf <button class="btn btn-warning">Reject</button></form>
</div>
@endsection
