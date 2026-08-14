@extends('layouts.app')

@section('content')
<div class="card">
    <h1>Create Audit Engagement</h1>
    @include('staff-info.partials.form', ['action' => route('staff-info.store'), 'method' => 'POST', 'engagement' => null])
</div>
@endsection
