@extends('layouts.app')
@section('content')
<h4>Edit User</h4>
<form method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT')
@include('users.form')
<button class="btn btn-primary">Update</button>
</form>
@endsection
