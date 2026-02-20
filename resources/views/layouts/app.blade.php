<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="flex-1 p-6">
        @if(session('status'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script>
    document.querySelectorAll('[data-submenu-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.submenuToggle);
            target.classList.toggle('hidden');
        });
    });
</script>
</body>
</html>
