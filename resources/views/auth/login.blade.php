@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h4 class="mb-1">Welcome Back</h4>
                    <p class="text-muted mb-0">Sign in to access Audit Management System</p>
                </div>

                <form method="POST" action="{{ route('login') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control form-control-lg" name="password" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a class="small" href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    <button class="btn btn-primary btn-lg w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
