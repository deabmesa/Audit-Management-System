@extends('layouts.app')
@section('content')
<h4>Activity Log</h4>
<div class="card"><table class="table mb-0"><thead><tr><th>Date</th><th>Action</th><th>Entity</th><th>Metadata</th></tr></thead><tbody>
@foreach($logs as $log)
<tr><td>{{ $log->created_at }}</td><td>{{ $log->action }}</td><td>{{ class_basename($log->entity_type) }}#{{ $log->entity_id }}</td><td><code>{{ json_encode($log->metadata) }}</code></td></tr>
@endforeach
</tbody></table></div>
@endsection
