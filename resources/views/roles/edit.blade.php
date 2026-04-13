@extends('layouts.app')
@section('content')
<h4>Edit Role - {{ $role->name }}</h4>
<form method="POST" action="{{ route('roles.update', $role) }}" class="card card-body">@csrf @method('PUT')
<input class="form-control mb-2" name="name" value="{{ $role->name }}" required>
<div class="row">@foreach($permissions as $permission)<div class="col-md-4"><label><input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->hasPermissionTo($permission->name))> {{ $permission->name }}</label></div>@endforeach</div>
<button class="btn btn-primary mt-3">Update</button>
</form>
@endsection
