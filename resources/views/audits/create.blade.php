@extends('layouts.app')
@section('content')
<h1>Create Audit</h1>
<form method="POST" action="{{ route('audits.store') }}">
    @csrf
    <input name="title" placeholder="Title" required>
    <input name="business_unit" placeholder="Business unit" required>
    <input name="audit_owner" placeholder="Audit owner" required>
    <input type="date" name="audit_date" required>
    <select name="status"><option>Planned</option><option>Ongoing</option><option>Completed</option></select>
    <button type="submit">Save</button>
</form>
@endsection
