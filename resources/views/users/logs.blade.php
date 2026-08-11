@extends('layouts.app')
@section('content')
<h4>User Activity Logs</h4>
<table class="table table-striped"><thead><tr><th>User</th><th>Activity</th><th>IP</th><th>Date</th></tr></thead><tbody>
@foreach($logs as $log)
<tr><td>{{ $log->user->name }}</td><td>{{ $log->activity }}</td><td>{{ $log->ip_address }}</td><td>{{ $log->created_at }}</td></tr>
@endforeach
</tbody></table>
@endsection
