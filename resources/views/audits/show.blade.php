@extends('layouts.app')
@section('content')
<h1>{{ $audit->title }}</h1>
<p>{{ $audit->business_unit }} | {{ $audit->audit_owner }} | {{ $audit->audit_date }} | {{ $audit->status }}</p>
<a href="{{ route('audits.issues.create', $audit) }}">Add Issue</a>

<h3>Attachments</h3>
<form action="{{ route('audits.attachments.store', $audit) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
@foreach($audit->attachments as $attachment)
    <div><a href="{{ route('attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></div>
@endforeach

<h3>Issues</h3>
@foreach($audit->issues as $issue)
    <div><a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a> ({{ $issue->status }})</div>
@endforeach
@endsection
