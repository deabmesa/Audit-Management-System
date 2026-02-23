<!DOCTYPE html>
<html><head><title>Login</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-5">
<h3>Audit Management Login</h3>
<form method="POST" action="/login" class="mt-3">@csrf
<input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-2" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary">Sign In</button>
</form>
<p class="mt-3 text-muted">In production install Laravel Breeze for full authentication controllers, password reset, and verification.</p>
</body></html>
