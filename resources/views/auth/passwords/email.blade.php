@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>Reset Password</h4>
<form method="POST" action="{{ route('password.email') }}">@csrf
<div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
<button class="btn btn-primary">Send Reset Link</button>
</form>
</div></div></div></div>
@endsection
