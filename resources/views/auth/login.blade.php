@extends('layouts.app')

@section('content')
<div class="card" style="max-width:420px;margin:2rem auto;">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login.perform') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</div>
@endsection
