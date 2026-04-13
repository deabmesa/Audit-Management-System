@extends('layouts.app')
@section('content')
<h4>Approval Timeline - {{ $finding->title }}</h4>
<ul class="list-group">
@foreach($timeline as $event)
<li class="list-group-item">{{ $event->created_at }} · {{ $event->step->name }} · <strong>{{ $event->action }}</strong> · {{ $event->remarks }}</li>
@endforeach
</ul>
@endsection
