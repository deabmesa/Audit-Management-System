@extends('layouts.app')
@section('content')
<h4>Recommendations Tracking</h4>
<form method="POST" action="{{ route('recommendations.store') }}" class="row g-2 mb-3">@csrf
<div class="col"><select class="form-select" name="finding_id">@foreach($findings as $finding)<option value="{{ $finding->id }}">{{ $finding->title }}</option>@endforeach</select></div>
<div class="col"><input class="form-control" name="owner" placeholder="Owner"></div>
<div class="col"><input type="date" class="form-control" name="due_date"></div>
<div class="col"><select class="form-select" name="implementation_status"><option>Open</option><option>In Progress</option><option>Closed</option></select></div>
<div class="col"><button class="btn btn-primary">Create</button></div>
</form>
<table class="table"><tr><th>Finding</th><th>Owner</th><th>Due Date</th><th>Status</th></tr>
@foreach($recommendations as $recommendation)
<tr><td>{{ $recommendation->finding->title }}</td><td>{{ $recommendation->owner }}</td><td>{{ $recommendation->due_date }}</td><td>{{ $recommendation->implementation_status }}</td></tr>
@endforeach
</table>
{{ $recommendations->links() }}
@endsection
