@extends('layouts.app')
@section('content')
<h4>Audit Registration & Assignment</h4>
<form class="row g-2 mb-3" method="POST" action="{{ route('audits.store') }}">@csrf
    <div class="col"><input class="form-control" name="title" placeholder="Audit Title" required></div>
    <div class="col"><input class="form-control" name="audit_type" placeholder="Audit Type" required></div>
    <div class="col"><input class="form-control" name="department" placeholder="Department" required></div>
    <div class="col"><input class="form-control" name="risk_category" placeholder="Risk Category" required></div>
    <div class="col"><input type="date" class="form-control" name="start_date" required></div>
    <div class="col"><input type="date" class="form-control" name="end_date"></div>
    <div class="col"><input class="form-control" name="status" value="Planned"></div>
    <div class="col"><button class="btn btn-primary">Save</button></div>
</form>
<table class="table table-bordered"><tr><th>Title</th><th>Department</th><th>Status</th><th>Assign Auditor</th></tr>
@foreach($audits as $audit)
<tr>
<td>{{ $audit->title }}</td><td>{{ $audit->department }}</td><td>{{ $audit->status }}</td>
<td><form method="POST" action="{{ route('audits.assign', $audit) }}">@csrf
<select class="form-select" name="user_id" required>@foreach($auditors as $auditor)<option value="{{ $auditor->id }}">{{ $auditor->name }}</option>@endforeach</select>
<button class="btn btn-sm btn-secondary mt-1">Assign</button></form></td>
</tr>
@endforeach
</table>
{{ $audits->links() }}
@endsection
