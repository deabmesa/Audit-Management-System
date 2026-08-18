@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>Register</h4>
<form method="POST" action="{{ route('register') }}">@csrf
<div class="mb-3"><label>Name</label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
<div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
<div class="mb-3"><label>Confirm</label><input type="password" class="form-control" name="password_confirmation" required></div>
<button class="btn btn-primary">Register</button>
</form>
</div></div></div></div>
@endsection
