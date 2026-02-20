<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Management</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('audits.index') }}">Audits</a>
</nav>
@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
@yield('content')
</body>
</html>
