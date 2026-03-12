<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Management System</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex">
            <a class="navbar-brand" href="{{ route('dashboard') }}">AuditMS</a>
            @auth
                <div class="navbar-nav">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="nav-link" href="{{ route('audits.index') }}">Audits</a>
                    @if(auth()->user()->hasRole('Admin'))
                        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                        <a class="nav-link" href="{{ route('users.logs') }}">Activity Logs</a>
                    @endif
                </div>
            @endauth
        </div>
        <div>
            @auth
                <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn btn-sm btn-light">Logout</button></form>
            @else
                <a class="btn btn-sm btn-light" href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>
</nav>
<div class="container">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</div>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
