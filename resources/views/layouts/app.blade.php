<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Audit Management System') }}</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #1f2937; color: #fff; padding: 1rem; }
        .sidebar a { display: block; color: #fff; text-decoration: none; margin: .5rem 0; }
        .content { flex: 1; padding: 1.5rem; }
        .card { background: #fff; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .topbar { display: flex; justify-content: flex-end; gap: .5rem; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: .5rem; text-align: left; }
        .row { display: flex; gap: 1rem; flex-wrap: wrap; }
        .col { flex: 1; min-width: 220px; }
    </style>
</head>
<body>
<div class="container">
    @auth
    <aside class="sidebar">
        <h3>AMS</h3>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('staff-info.index') }}">Staff Info</a>
        <a href="{{ route('pams.index') }}">PAMS</a>
    </aside>
    @endauth

    <main class="content">
        <div class="topbar">
            @auth
            <form method="POST" action="{{ route('checkout.perform') }}">@csrf<button type="submit">Check-Out</button></form>
            <form method="POST" action="{{ route('logout.perform') }}">@csrf<button type="submit">Logout</button></form>
            @endauth
        </div>
        @if($errors->any())
            <div class="card">{{ implode(' ', $errors->all()) }}</div>
        @endif
        @if(session('status'))
            <div class="card">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
