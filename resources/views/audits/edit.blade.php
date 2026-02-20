@extends('layouts.app')
@section('content')
<h1>Edit Audit</h1>
<form method="POST" action="{{ route('audits.update', $audit) }}">
    @csrf @method('PUT')
    <input name="title" value="{{ $audit->title }}" required>
    <input name="business_unit" value="{{ $audit->business_unit }}" required>
    <input name="audit_owner" value="{{ $audit->audit_owner }}" required>
    <input type="date" name="audit_date" value="{{ $audit->audit_date }}" required>
    <select name="status"><option {{ $audit->status==='Planned'?'selected':'' }}>Planned</option><option {{ $audit->status==='Ongoing'?'selected':'' }}>Ongoing</option><option {{ $audit->status==='Completed'?'selected':'' }}>Completed</option></select>
    <button type="submit">Update</button>
</form>
@endsection
