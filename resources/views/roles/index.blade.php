@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3"><h4>Roles & Permissions</h4><a class="btn btn-primary" href="{{ route('roles.create') }}">Create Role</a></div>
<div class="row g-3">
@foreach($roles as $role)
<div class="col-md-4"><div class="card"><div class="card-body">
<h6>{{ $role->name }}</h6>
<p class="small text-muted">{{ $role->permissions->pluck('name')->join(', ') }}</p>
<a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-dark">Edit</a>
</div></div></div>
@endforeach
</div>
@endsection
