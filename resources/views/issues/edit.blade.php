@extends('layouts.app')
@section('content')
<h1>Edit Issue</h1>
<form method="POST" action="{{ route('issues.update', $issue) }}">
    @csrf @method('PUT')
    <input name="title" value="{{ $issue->title }}" required>
    <textarea name="description" required>{{ $issue->description }}</textarea>
    <input name="risk_level" value="{{ $issue->risk_level }}" required>
    <textarea name="recommendation" required>{{ $issue->recommendation }}</textarea>
    <input type="date" name="due_date" value="{{ $issue->due_date }}" required>
    <input name="responsible_person" value="{{ $issue->responsible_person }}" required>
    <select name="status"><option {{ $issue->status==='Open'?'selected':'' }}>Open</option><option {{ $issue->status==='In Progress'?'selected':'' }}>In Progress</option><option {{ $issue->status==='Closed'?'selected':'' }}>Closed</option></select>
    <button type="submit">Update</button>
</form>
@endsection
