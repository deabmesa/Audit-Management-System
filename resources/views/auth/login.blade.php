<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign In — Audit System</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script defer src="/js/alpine.min.js"></script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;min-height:100vh;display:flex;background:#eef1f7}
/* left panel */
.lp{
  width:42%;background:linear-gradient(160deg,#0d2137 0%,#081828 100%);
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  padding:48px 44px;position:relative;overflow:hidden;
}
.lp::before{content:'';position:absolute;top:-110px;right:-80px;width:380px;height:380px;border-radius:50%;background:rgba(37,99,235,.18)}
.lp::after{content:'';position:absolute;bottom:-90px;left:-50px;width:300px;height:300px;border-radius:50%;background:rgba(245,158,11,.09)}
.lp-c{position:relative;z-index:2;max-width:320px}
.lp-logo{width:300px;height:60px;border-radius:13px;object-fit:contain;display:block}
.lp-title{font-size:28px;font-weight:700;color:#fff;line-height:1.28;letter-spacing:-.5px;margin-bottom:12px}
.lp-title span{color:#f59e0b}
.lp-desc{font-size:13.5px;color:rgba(255,255,255,.48);line-height:1.7;margin-bottom:34px}
.feat{list-style:none}
.feat li{display:flex;align-items:center;gap:11px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px;color:rgba(255,255,255,.64)}
.feat li:last-child{border-bottom:none}
.feat-ico{width:30px;height:30px;border-radius:8px;background:rgba(255,255,255,.07);display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:15px;flex-shrink:0}
/* right panel */
.rp{flex:1;display:flex;align-items:center;justify-content:center;padding:32px}
.box{background:#fff;border-radius:18px;padding:42px;width:100%;max-width:420px;box-shadow:0 4px 40px rgba(0,0,0,.07)}
.box-title{font-size:24px;font-weight:700;color:#0c1829;letter-spacing:-.4px;margin-bottom:4px}
.box-sub{font-size:13px;color:#96a8be;margin-bottom:28px}
.err{background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:10px 13px;color:#b91c1c;font-size:13px;display:flex;align-items:center;gap:7px;margin-bottom:18px}
.lbl{font-size:11px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#52637a;margin-bottom:5px;display:block}
.iw{position:relative;margin-bottom:16px}
.iw .il{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:15px;color:#c0cde0;pointer-events:none}
.iw .ir{position:absolute;right:10px;top:50%;transform:translateY(-50%)}
input{width:100%;height:46px;padding:0 40px 0 38px;border:1.5px solid #e2e8f4;border-radius:9px;font-family:inherit;font-size:13.5px;color:#0c1829;background:#fafbfc;outline:none;transition:border-color .14s,box-shadow .14s}
input::placeholder{color:#c0cde0}
input:focus{border-color:#0d2137;background:#fff;box-shadow:0 0 0 3px rgba(13,33,55,.09)}
.eye-btn{border:none;background:none;color:#96a8be;cursor:pointer;font-size:15px;padding:3px;border-radius:5px;transition:color .12s}
.eye-btn:hover{color:#52637a}
.row-mid{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.rem{display:flex;align-items:center;gap:6px;font-size:13px;color:#52637a;cursor:pointer}
.rem input[type=checkbox]{accent-color:#0d2137;width:14px;height:14px}
.forgot{font-size:13px;color:#0d2137;font-weight:500;text-decoration:none}
.forgot:hover{text-decoration:underline}
.btn-sub{width:100%;height:46px;background:linear-gradient(135deg,#0d2137,#1a3e6e);color:#fff;border:none;border-radius:9px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .14s}
.btn-sub:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(13,33,55,.28)}
.btn-sub:active{transform:none}
.btn-sub:disabled{opacity:.55;cursor:not-allowed;transform:none}
.copy{text-align:center;margin-top:24px;font-size:12px;color:#c0cde0}
@media(max-width:768px){.lp{display:none}.rp{padding:20px}.box{padding:28px 22px}}
</style>
</head>
<body>

<div class="lp">
  <div class="lp-c">
    <img src="/images/logo.png" class="lp-logo" alt="Logo">
    <h1 class="lp-title">Internal <span>Audit</span><br>Management System</h1>
    <p class="lp-desc">Streamline audit engagements, track findings, and manage governance — all in one place.</p>
    <ul class="feat">
      <li><div class="feat-ico"><i class="bi bi-clipboard2-check"></i></div>Audit Engagement Tracking</li>
      <li><div class="feat-ico"><i class="bi bi-bar-chart-line"></i></div>Real-time KPI Dashboard</li>
      <li><div class="feat-ico"><i class="bi bi-shield-check"></i></div>Role-based Access Control</li>
      <li><div class="feat-ico"><i class="bi bi-bell"></i></div>Follow-up &amp; Alerts</li>
      <li><div class="feat-ico"><i class="bi bi-people"></i></div>Staff &amp; Attendance Management</li>
    </ul>
  </div>
</div>

<div class="rp">
  <div class="box" x-data="{ sp:false, loading:false }">
    <h2 class="box-title">Welcome back</h2>
    <p class="box-sub">Sign in to your account to continue</p>

    @if($errors->has('login'))
      <div class="err"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first('login') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" @submit="loading=true">
      @csrf
      <label class="lbl">Username / Email</label>
      <div class="iw">
        <i class="bi bi-person il"></i>
        <input type="text" name="email" placeholder="Enter your username"
               value="{{ old('email') }}" required autocomplete="username">
      </div>

      <label class="lbl">Password</label>
      <div class="iw">
        <i class="bi bi-lock il"></i>
        <input :type="sp ? 'text' : 'password'" name="password"
               placeholder="Enter your password" required autocomplete="current-password">
        <div class="ir">
          <button type="button" class="eye-btn" @click="sp = !sp">
            <i class="bi" :class="sp ? 'bi-eye-slash' : 'bi-eye'"></i>
          </button>
        </div>
      </div>

      <div class="row-mid">
        <label class="rem"><input type="checkbox" name="remember"> Remember me</label>
        <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
      </div>

      <button type="submit" class="btn-sub" :disabled="loading">
        <span x-show="!loading"><i class="bi bi-box-arrow-in-right"></i> Sign In</span>
        <span x-show="loading" x-cloak>Signing in…</span>
      </button>
    </form>

    <p class="copy">&copy; {{ date('Y') }} Audit System &mdash; All rights reserved.</p>
  </div>
</div>
</body>
</html>
