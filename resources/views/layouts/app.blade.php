<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Audit Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="d-flex">
    <aside class="bg-dark text-white p-3" style="width: 260px; min-height: 100vh;">
        <h5 class="mb-4">Audit Management</h5>
        <ul class="nav flex-column gap-2">
            <li><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a class="nav-link text-white" href="{{ route('staff.index') }}">Staff Info</a></li>
            <li><a class="nav-link text-white" href="{{ route('audits.index') }}">Audit Registration</a></li>
            <li><a class="nav-link text-white" href="{{ route('plans.index') }}">PAMS Planning</a></li>
            <li><a class="nav-link text-white" href="{{ route('workpapers.index') }}">PAMS Workpapers</a></li>
            <li><a class="nav-link text-white" href="{{ route('findings.index') }}">PAMS Findings</a></li>
            <li><a class="nav-link text-white" href="{{ route('recommendations.index') }}">Recommendations</a></li>
            <li><a class="nav-link text-white" href="{{ route('reports.financial') }}">Oracle Reports</a></li>
        </ul>
    </aside>
    <main class="flex-grow-1 p-4">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
