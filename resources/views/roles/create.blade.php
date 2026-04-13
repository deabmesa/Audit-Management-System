@extends('layouts.app')
@section('content')
<h4>Create Role</h4>
<form method="POST" action="{{ route('roles.store') }}" class="card card-body">@csrf
<input class="form-control mb-2" name="name" placeholder="Role name" required>
<div class="row">@foreach($permissions as $permission)<div class="col-md-4"><label><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->name }}</label></div>@endforeach</div>
<button class="btn btn-primary mt-3">Save</button>
</form>
@endsection
