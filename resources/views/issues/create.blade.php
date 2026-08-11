@extends('layouts.app')
@section('content')
<h1>Create Issue for {{ $audit->title }}</h1>
<form method="POST" action="{{ route('audits.issues.store', $audit) }}">
    @csrf
    <input name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input name="risk_level" placeholder="Risk level" required>
    <textarea name="recommendation" placeholder="Recommendation" required></textarea>
    <input type="date" name="due_date" required>
    <input name="responsible_person" placeholder="Responsible person" required>
    <select name="status"><option>Open</option><option>In Progress</option><option>Closed</option></select>
    <button type="submit">Save</button>
</form>
@endsection
