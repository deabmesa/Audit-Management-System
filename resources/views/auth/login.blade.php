@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>Login</h4>
<form method="POST" action="{{ route('login') }}">@csrf
<div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" required></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
<button class="btn btn-primary">Login</button>
</form>
</div></div></div></div>
@endsection
