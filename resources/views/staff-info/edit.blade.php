@extends('layouts.app')

@section('content')
<div class="card">
    <h1>Edit Audit Engagement</h1>
    @include('staff-info.partials.form', ['action' => route('staff-info.update', $engagement), 'method' => 'PUT', 'engagement' => $engagement])
</div>
@endsection
