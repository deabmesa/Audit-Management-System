@extends('layouts.app')
@section('content')
<h4>Create User</h4>
<form method="POST" action="{{ route('users.store') }}">@csrf
@include('users.form')
<button class="btn btn-primary">Save</button>
</form>
@endsection
