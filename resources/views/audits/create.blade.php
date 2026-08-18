@extends('layouts.app')
@section('content')
<h4>Create Audit Engagement</h4>
<form method="POST" action="{{ route('audits.store') }}">@csrf
<div class="mb-3"><label>Title</label><input class="form-control" name="title"></div>
<div class="mb-3"><label>Scope</label><textarea class="form-control" name="scope"></textarea></div>
<div class="mb-3"><label>Risk Level</label><select name="risk_level" class="form-select"><option>Low</option><option>Medium</option><option>High</option></select></div>
<div class="row"><div class="col"><label>Start</label><input type="date" class="form-control" name="planned_start_date"></div><div class="col"><label>End</label><input type="date" class="form-control" name="planned_end_date"></div></div>
<div class="mb-3 mt-3"><label>Status</label><select name="status" class="form-select"><option>Planned</option><option>In Progress</option><option>Completed</option></select></div>
<div class="mb-3"><label>Assign Auditors</label><select name="auditor_ids[]" multiple class="form-select">@foreach($auditors as $auditor)<option value="{{ $auditor->id }}">{{ $auditor->name }}</option>@endforeach</select></div>
<button class="btn btn-primary">Save</button>
</form>
@endsection
