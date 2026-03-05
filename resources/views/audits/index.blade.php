@extends('layouts.app')
@section('content')
<h1>Audits</h1>
<table class="table table-striped">
<thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Owner</th></tr></thead>
<tbody>
@foreach($audits as $audit)
<tr><td>{{ $audit->title }}</td><td>{{ $audit->category }}</td><td>{{ $audit->status }}</td><td>{{ $audit->owner?->name }}</td></tr>
@endforeach
</tbody>
</table>
{{ $audits->links() }}
@endsection
