<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Management System</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #fff7f0; margin: 0; color: #2e2e2e; }
        .container { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 12px 24px rgba(0,0,0,.08); padding: 20px; }
        .btn { background: #f97316; color: #fff; border: 0; border-radius: 10px; padding: 10px 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #ea580c; }
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .title { text-align: center; font-size: 2rem; color: #c2410c; margin-bottom: 24px; }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>
