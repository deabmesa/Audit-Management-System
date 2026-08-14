@extends('layouts.app')
@section('content')
<h4>Staff Management</h4>
<form class="row g-2 mb-3" method="POST" action="{{ route('staff.store') }}">@csrf
    <div class="col"><input class="form-control" name="name" placeholder="Name" required></div>
    <div class="col"><input class="form-control" name="email" placeholder="Email" required></div>
    <div class="col"><input class="form-control" name="department" placeholder="Department" required></div>
    <div class="col"><input class="form-control" name="position" placeholder="Position" required></div>
    <div class="col"><select class="form-select" name="status"><option>Active</option><option>Inactive</option></select></div>
    <div class="col"><button class="btn btn-primary">Create</button></div>
</form>
<table class="table table-striped"><tr><th>Name</th><th>Email</th><th>Department</th><th>Position</th><th>Status</th></tr>
@foreach($staff as $member)
<tr><td>{{ $member->user->name }}</td><td>{{ $member->user->email }}</td><td>{{ $member->department }}</td><td>{{ $member->position }}</td><td>{{ $member->status }}</td></tr>
@endforeach
</table>
{{ $staff->links() }}
@endsection
