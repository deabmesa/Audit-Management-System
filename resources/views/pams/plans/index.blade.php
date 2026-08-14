@extends('layouts.app')
@section('content')
<h4>Audit Planning</h4>
<form method="POST" action="{{ route('plans.store') }}" class="row g-2 mb-3">@csrf
<div class="col"><select class="form-select" name="audit_id">@foreach($audits as $audit)<option value="{{ $audit->id }}">{{ $audit->title }}</option>@endforeach</select></div>
<div class="col-12"><textarea class="form-control" name="objectives" placeholder="Objectives"></textarea></div>
<div class="col-12"><textarea class="form-control" name="scope" placeholder="Scope"></textarea></div>
<div class="col-12"><textarea class="form-control" name="risk_assessment" placeholder="Risk Assessment"></textarea></div>
<div class="col-12"><textarea class="form-control" name="control_areas" placeholder="Control Areas"></textarea></div>
<div class="col-12"><button class="btn btn-primary">Save Plan</button></div>
</form>
<table class="table"><tr><th>Audit</th><th>Objectives</th><th>Scope</th></tr>
@foreach($plans as $plan)
<tr><td>{{ $plan->audit->title }}</td><td>{{ \Illuminate\Support\Str::limit($plan->objectives,80) }}</td><td>{{ \Illuminate\Support\Str::limit($plan->scope,80) }}</td></tr>
@endforeach
</table>
{{ $plans->links() }}
@endsection
