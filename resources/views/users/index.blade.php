@extends('layouts.app')
@section('content')
<h1>User Management</h1>
<form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data" class="row g-3 mb-4">
    @csrf
    <div class="col-md-4"><input class="form-control" name="name" placeholder="Name" required></div>
    <div class="col-md-4"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
    <div class="col-md-4"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
    <div class="col-md-4"><input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required></div>
    <div class="col-md-4"><select class="form-select" name="role">@foreach($roles as $role)<option>{{ $role->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><input type="file" class="form-control" name="profile_image" accept="image/*"></div>
    <div class="col-12"><button class="btn btn-primary">Create User</button></div>
</form>
<table class="table">
<thead><tr><th>Name</th><th>Email</th><th>Roles</th></tr></thead>
<tbody>@foreach($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->join(', ') }}</td></tr>@endforeach</tbody>
</table>
{{ $users->links() }}
@endsection
