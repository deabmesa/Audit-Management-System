@extends('layouts.app')
@section('content')
<h4>Edit Finding</h4>
<form method="POST" action="{{ route('findings.update',$finding) }}" class="card card-body">
@csrf @method('PUT')
<input class="form-control mb-2" name="title" value="{{ $finding->title }}" required>
<textarea class="form-control mb-2" name="description" rows="4" required>{{ $finding->description }}</textarea>
<select name="severity" class="form-select mb-2">@foreach(['low','medium','high','critical'] as $s)<option value="{{ $s }}" @selected($finding->severity === $s)>{{ ucfirst($s) }}</option>@endforeach</select>
<select name="status" class="form-select mb-2">@foreach(['open','review','approved','closed'] as $s)<option value="{{ $s }}" @selected($finding->status === $s)>{{ ucfirst($s) }}</option>@endforeach</select>
<button class="btn btn-primary">Update</button>
</form>
@endsection
