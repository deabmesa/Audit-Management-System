@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h4>Audit Engagements</h4><a class="btn btn-primary" href="{{ route('audits.create') }}">New Audit</a></div>
<table class="table table-bordered"><thead><tr><th>Title</th><th>Risk</th><th>Status</th><th>Dates</th><th></th></tr></thead><tbody>
@foreach($audits as $audit)
<tr><td>{{ $audit->title }}</td><td>{{ $audit->risk_level }}</td><td>{{ $audit->status }}</td><td>{{ $audit->planned_start_date }} - {{ $audit->planned_end_date }}</td><td><a class="btn btn-sm btn-info" href="{{ route('audits.show', $audit) }}">View</a></td></tr>
@endforeach
</tbody></table>
@endsection
