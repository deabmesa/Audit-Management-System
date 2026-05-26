@extends('layouts.app')
@section('page-title','Register')
@section('content')
<div style="max-width:500px;margin:0 auto">
  <div class="ph"><div class="ph-info"><h1>Register</h1><p>Create a new account</p></div></div>
  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-person-plus" style="color:var(--c-blue)"></i>New Account</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('register') }}">@csrf
        <div class="form-group"><label class="form-label">Name <span class="req">*</span></label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
        <div class="form-group"><label class="form-label">Email <span class="req">*</span></label><input type="email" class="form-control" name="email" value="{{ old('email') }}" required></div>
        <div class="form-group"><label class="form-label">Password <span class="req">*</span></label><input type="password" class="form-control" name="password" required></div>
        <div class="form-group"><label class="form-label">Confirm Password <span class="req">*</span></label><input type="password" class="form-control" name="password_confirmation" required></div>
        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Register</button>
      </form>
    </div>
  </div>
</div>
@endsection
