@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Findings</h4>
    <a href="{{ route('findings.create') }}" class="btn btn-primary">New Finding</a>
</div>
<form class="mb-3"><select class="form-select w-auto" name="status" onchange="this.form.submit()"><option value="">All Statuses</option>@foreach(['open','review','approved','closed'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></form>
<div class="card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Title</th><th>Severity</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($findings as $finding)
<tr><td>{{ $finding->title }}</td><td>{{ strtoupper($finding->severity) }}</td><td>{{ ucfirst($finding->status) }}</td><td><a href="{{ route('findings.show',$finding) }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>
@endforeach
</tbody></table></div></div>
<div class="mt-3">{{ $findings->links() }}</div>
@endsection
