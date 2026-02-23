@extends('layouts.app')

@section('content')
<h1>Dashboard</h1>
<div class="row">
    <div class="card col"><h3>Total Engagements</h3><p>{{ $engagementCount }}</p></div>
    <div class="card col"><h3>Open Engagements</h3><p>{{ $openEngagementCount }}</p></div>
    <div class="card col"><h3>PAMS Reports</h3><p>{{ $reportCount }}</p></div>
</div>
@endsection
