@extends('layouts.app')
@section('content')
<h1>{{ $issue->title }}</h1>
<p>Audit: {{ $issue->audit->title }} | Status: {{ $issue->status }} | Due: {{ $issue->due_date }}</p>
<p>{{ $issue->description }}</p>
<p><strong>Recommendation:</strong> {{ $issue->recommendation }}</p>

<h3>Attachments</h3>
<form action="{{ route('issues.attachments.store', $issue) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
@foreach($issue->attachments as $attachment)
    <div><a href="{{ route('attachments.download', $attachment) }}">{{ $attachment->original_name }}</a></div>
@endforeach
@endsection
