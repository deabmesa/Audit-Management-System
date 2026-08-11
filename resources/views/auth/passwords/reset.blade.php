@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>Set New Password</h4>
<form method="POST" action="{{ route('password.update') }}">@csrf
<input type="hidden" name="token" value="{{ $token }}">
<div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" value="{{ old('email', $email) }}" required></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
<div class="mb-3"><label>Confirm Password</label><input type="password" class="form-control" name="password_confirmation" required></div>
<button class="btn btn-primary">Reset Password</button>
</form>
</div></div></div></div>
@endsection
