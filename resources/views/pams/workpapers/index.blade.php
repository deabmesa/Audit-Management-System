@extends('layouts.app')
@section('content')
<h4>Workpapers</h4>
<form method="POST" action="{{ route('workpapers.store') }}" enctype="multipart/form-data" class="row g-2 mb-3">@csrf
<div class="col"><select class="form-select" name="audit_id">@foreach($audits as $audit)<option value="{{ $audit->id }}">{{ $audit->title }}</option>@endforeach</select></div>
<div class="col"><select class="form-select" name="status"><option>Open</option><option>In Progress</option><option>Closed</option></select></div>
<div class="col-12"><textarea class="form-control" name="procedure" placeholder="Audit Procedure"></textarea></div>
<div class="col"><input class="form-control" type="file" name="evidence"></div>
<div class="col"><button class="btn btn-primary">Save Workpaper</button></div>
</form>
<table class="table"><tr><th>Audit</th><th>Procedure</th><th>Status</th><th>Evidence</th></tr>
@foreach($workpapers as $workpaper)
<tr><td>{{ $workpaper->audit->title }}</td><td>{{ \Illuminate\Support\Str::limit($workpaper->procedure,90) }}</td><td>{{ $workpaper->status }}</td><td>{{ $workpaper->evidence_path ?? '-' }}</td></tr>
@endforeach
</table>
{{ $workpapers->links() }}
@endsection
