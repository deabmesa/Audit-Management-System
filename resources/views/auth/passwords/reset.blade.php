<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reset Password — Audit System</title>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#eef1f7}
.box{background:#fff;border-radius:18px;padding:42px;width:100%;max-width:420px;box-shadow:0 4px 40px rgba(0,0,0,.07)}
.logo{width:44px;height:44px;border-radius:11px;background:#0d2137;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:16px;margin-bottom:22px}
h2{font-size:22px;font-weight:700;color:#0c1829;margin-bottom:6px}
p{font-size:13px;color:#96a8be;margin-bottom:24px}
.lbl{font-size:11px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#52637a;margin-bottom:5px;display:block}
input{width:100%;height:44px;padding:0 12px;border:1.5px solid #e2e8f4;border-radius:8px;font-family:inherit;font-size:13px;color:#0c1829;background:#fafbfc;outline:none;margin-bottom:16px;transition:border-color .14s,box-shadow .14s}
input:focus{border-color:#0d2137;background:#fff;box-shadow:0 0 0 3px rgba(13,33,55,.09)}
.btn{width:100%;height:44px;background:#0d2137;color:#fff;border:none;border-radius:8px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;transition:background .13s}
.btn:hover{background:#163554}
</style>
</head>
<body>
<div class="box">
  <div class="logo">AS</div>
  <h2>Set New Password</h2>
  <p>Choose a strong password for your account.</p>
  <form method="POST" action="{{ route('password.update') }}">@csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <label class="lbl">Email Address</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <label class="lbl">New Password</label>
    <input type="password" name="password" required>
    <label class="lbl">Confirm Password</label>
    <input type="password" name="password_confirmation" required>
    <button type="submit" class="btn">Reset Password</button>
  </form>
</div>
</body>
</html>
