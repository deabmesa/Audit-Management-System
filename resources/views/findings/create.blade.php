@extends('layouts.app')
@section('content')
<h4>Create Finding</h4>
<form method="POST" action="{{ route('findings.store') }}" class="card card-body">
@csrf
<input class="form-control mb-2" name="title" placeholder="Title" required>
<textarea class="form-control mb-2" name="description" rows="4" placeholder="Description" required></textarea>
<select name="severity" class="form-select mb-2" required><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option><option value="critical">Critical</option></select>
<button class="btn btn-primary">Save</button>
</form>
@endsection
