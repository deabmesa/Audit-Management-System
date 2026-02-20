@extends('layouts.app')
@section('content')
<h1>Audits</h1>
<a href="{{ route('audits.create') }}">Create Audit</a>
@foreach($audits as $audit)
    <div>
        <a href="{{ route('audits.show', $audit) }}">{{ $audit->title }}</a> - {{ $audit->status }}
    </div>
@endforeach
{{ $audits->links() }}
@endsection
