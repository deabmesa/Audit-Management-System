<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audit Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <nav class="bg-dark text-white p-3" style="width: 280px; min-height: 100vh;">
        <h5>AuditMS</h5>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item text-white mt-2">Develop Task</li>
            <li><a class="nav-link text-white-50" href="#">Task List</a></li>
            <li><a class="nav-link text-white-50" href="#">Assign Task</a></li>
            <li><a class="nav-link text-white-50" href="#">Task Tracking</a></li>
            <li class="nav-item text-white mt-2">Pre-Audit Work</li>
            <li><a class="nav-link text-white-50" href="#">Planning</a></li>
            <li><a class="nav-link text-white-50" href="#">Risk Assessment</a></li>
            <li><a class="nav-link text-white-50" href="#">Audit Program</a></li>
            <li class="nav-item text-white mt-2">Audit Fieldwork</li>
            <li><a class="nav-link text-white-50" href="#">Audit Testing</a></li>
            <li><a class="nav-link text-white-50" href="#">Evidence Upload</a></li>
            <li><a class="nav-link text-white-50" href="#">Findings</a></li>
            <li class="nav-item text-white mt-2">Audit Report</li>
            <li><a class="nav-link text-white-50" href="#">Draft Reports</a></li>
            <li><a class="nav-link text-white-50" href="#">Final Reports</a></li>
            <li class="nav-item text-white mt-2">Management Report</li>
            <li><a class="nav-link text-white-50" href="#">KPI Reports</a></li>
            <li><a class="nav-link text-white-50" href="#">Statistics</a></li>
            <li class="nav-item text-white mt-2">Settings</li>
            <li><a class="nav-link text-white-50" href="#">System settings</a></li>
            <li><a class="nav-link text-white-50" href="#">Risk levels</a></li>
            <li><a class="nav-link text-white-50" href="#">Audit categories</a></li>
            <li class="nav-item"><a class="nav-link text-white mt-2" href="#">Contact List</a></li>
        </ul>
    </nav>
    <main class="p-4 w-100">
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('scripts')
</body>
</html>
