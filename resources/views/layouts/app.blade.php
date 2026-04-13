<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audit Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .sidebar { width: 250px; min-height: 100vh; background: #111827; }
        .sidebar a { color: #d1d5db; text-decoration: none; display: block; padding: .75rem 1rem; }
        .sidebar a:hover { background: #1f2937; color: #fff; }
        .fade-in { animation: fadeIn .4s ease-in; }
        @keyframes fadeIn { from {opacity:0; transform: translateY(8px)} to {opacity:1; transform:none} }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="sidebar">
        <h5 class="text-white p-3 mb-0">Audit Suite</h5>
        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('findings.index') }}"><i class="bi bi-search me-2"></i>Findings</a>
        @role('Admin')
        <a href="{{ route('roles.index') }}"><i class="bi bi-people me-2"></i>Roles</a>
        <a href="{{ route('menus.index') }}"><i class="bi bi-list-nested me-2"></i>Menus</a>
        <a href="{{ route('workflow.designer') }}"><i class="bi bi-diagram-3 me-2"></i>Workflow</a>
        @endrole
        <a href="{{ route('activity-logs.index') }}"><i class="bi bi-clock-history me-2"></i>Activity</a>
    </aside>
    <main class="flex-grow-1">
        <nav class="navbar navbar-expand-lg bg-white border-bottom px-3">
            <div class="ms-auto d-flex gap-2">
                <button id="themeToggle" class="btn btn-outline-dark btn-sm"><i class="bi bi-moon"></i></button>
                <form method="POST" action="/logout">@csrf <button class="btn btn-danger btn-sm">Logout</button></form>
            </div>
        </nav>
        <div class="container-fluid p-4 fade-in">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const savedTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-bs-theme', savedTheme);
document.getElementById('themeToggle').addEventListener('click',()=>{
  const current = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-bs-theme', current); localStorage.setItem('theme', current);
});
</script>
@stack('scripts')
</body>
</html>
