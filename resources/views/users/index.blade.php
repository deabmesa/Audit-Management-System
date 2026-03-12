@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Users</h4>
    <a class="btn btn-primary" href="{{ route('users.create') }}">Add User</a>
</div>
<table class="table table-bordered">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role }}</td>
        <td>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
            <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection
