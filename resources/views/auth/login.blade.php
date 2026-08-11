<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login</title><script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script></head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
<form method="POST" action="{{ route('login.store') }}" class="bg-white shadow rounded p-8 w-full max-w-md space-y-4">
    @csrf
    <h1 class="text-2xl font-bold">Audit Management Login</h1>
    <input name="login" type="text" placeholder="Email or Username" required class="w-full border rounded p-2">
    <input name="password" type="password" placeholder="Password" required class="w-full border rounded p-2">
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Remember me</label>
    <button class="w-full bg-cyan-700 text-white py-2 rounded">Sign in</button>
</form>
</body></html>
