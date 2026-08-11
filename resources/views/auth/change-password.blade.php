@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-4">Change Password</h1>
<form method="POST" action="{{ route('password.update') }}" class="bg-white p-5 rounded shadow max-w-lg space-y-3">
    @csrf @method('PUT')
    <input type="password" name="current_password" placeholder="Current Password" class="w-full border rounded p-2" required>
    <input type="password" name="password" placeholder="New Password" class="w-full border rounded p-2" required>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border rounded p-2" required>
    <button class="bg-cyan-700 text-white px-4 py-2 rounded">Update Password</button>
</form>
@endsection
