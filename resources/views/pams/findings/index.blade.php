@extends('layouts.app')
@section('content')
<h4>Findings Management</h4>
<form method="POST" action="{{ route('findings.store') }}" class="row g-2 mb-3">@csrf
<div class="col-2"><select class="form-select" name="audit_id">@foreach($audits as $audit)<option value="{{ $audit->id }}">{{ $audit->title }}</option>@endforeach</select></div>
<div class="col"><input class="form-control" name="title" placeholder="Finding Title"></div>
<div class="col"><select class="form-select" name="risk_level"><option>Low</option><option>Medium</option><option>High</option><option>Critical</option></select></div>
<div class="col"><input class="form-control" name="status" value="Open"></div>
<div class="col-12"><textarea class="form-control" name="observation" placeholder="Observation"></textarea></div>
<div class="col-12"><textarea class="form-control" name="impact" placeholder="Impact"></textarea></div>
<div class="col-12"><textarea class="form-control" name="root_cause" placeholder="Root Cause"></textarea></div>
<div class="col-12"><textarea class="form-control" name="recommendation_text" placeholder="Recommendation"></textarea></div>
<div class="col-12"><button class="btn btn-primary">Add Finding</button></div>
</form>
<table class="table"><tr><th>Audit</th><th>Title</th><th>Risk</th><th>Status</th></tr>
@foreach($findings as $finding)
<tr><td>{{ $finding->audit->title }}</td><td>{{ $finding->title }}</td><td>{{ $finding->risk_level }}</td><td>{{ $finding->status }}</td></tr>
@endforeach
</table>
{{ $findings->links() }}
@endsection
