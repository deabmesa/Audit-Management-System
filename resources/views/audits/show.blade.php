@extends('layouts.app')
@section('content')
<h4>{{ $audit->title }}</h4>
<p><b>Scope:</b> {{ $audit->scope }}</p>
<p><b>Risk:</b> {{ $audit->risk_level }} | <b>Status:</b> {{ $audit->status }}</p>

<div class="row">
<div class="col-md-6">
    <h5>Add Fieldwork</h5>
    <form method="POST" action="{{ route('fieldwork.store', $audit) }}" enctype="multipart/form-data">@csrf
        <input class="form-control mb-2" name="checklist_item" placeholder="Checklist item">
        <textarea class="form-control mb-2" name="working_paper" placeholder="Working paper"></textarea>
        <textarea class="form-control mb-2" name="audit_notes" placeholder="Audit notes"></textarea>
        <input class="form-control mb-2" type="file" name="evidence_file">
        <select class="form-select mb-2" name="status"><option>Pending</option><option>Completed</option></select>
        <button class="btn btn-success">Add</button>
    </form>
</div>
<div class="col-md-6">
    <h5>Add Finding</h5>
    <form method="POST" action="{{ route('findings.store', $audit) }}">@csrf
        <input class="form-control mb-2" name="title" placeholder="Finding title">
        <select class="form-select mb-2" name="risk_rating"><option>Low</option><option>Medium</option><option>High</option></select>
        <textarea class="form-control mb-2" name="root_cause" placeholder="Root cause"></textarea>
        <textarea class="form-control mb-2" name="recommendation" placeholder="Recommendation"></textarea>
        <textarea class="form-control mb-2" name="management_response" placeholder="Management response"></textarea>
        <button class="btn btn-danger">Add Finding</button>
    </form>
</div>
</div>

<hr>
<h5>Findings & Follow-Up</h5>
@foreach($audit->findings as $finding)
<div class="card mb-2"><div class="card-body">
    <h6>{{ $finding->title }} ({{ $finding->risk_rating }})</h6>
    <p>{{ $finding->recommendation }}</p>
    <form method="POST" action="{{ route('followups.store', $finding) }}">@csrf
        <div class="row g-2">
            <div class="col"><input class="form-control" name="recommendation_status" placeholder="Recommendation status"></div>
            <div class="col"><input type="date" class="form-control" name="due_date"></div>
            <div class="col"><select class="form-select" name="status"><option>Open</option><option>Closed</option></select></div>
        </div>
        <textarea class="form-control my-2" name="follow_up_comments" placeholder="Follow-up comments"></textarea>
        <button class="btn btn-outline-primary btn-sm">Save Follow-Up</button>
    </form>
</div></div>
@endforeach
@endsection
