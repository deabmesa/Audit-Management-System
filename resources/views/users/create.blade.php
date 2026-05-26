@extends('layouts.app')
@section('page-title','Create User')
@section('content')

<div class="ph">
  <div class="ph-info">
    <h1>Create User</h1>
    <p>Add a new user account and assign their role</p>
  </div>
  <div class="ph-acts">
    <a href="{{ route('users.index') }}" class="btn btn-outline">
      <i class="bi bi-arrow-left"></i> Back
    </a>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-err">
    <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0"></i>
    <div>
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  </div>
@endif

<div style="display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start">

  {{-- ═══ MAIN FORM ═══ --}}
  <div class="card">
    <div class="card-hd">
      <span class="card-title">
        <i class="bi bi-person-plus" style="color:var(--c-blue)"></i>
        Account Information
      </span>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('users.store') }}" id="createUserForm">
        @csrf

        {{-- Name only --}}
        <div class="form-group">
          <label class="form-label">Full Name <span class="req">*</span></label>
          <div class="iw">
            <i class="bi bi-person il"></i>
            <input class="form-control" name="name"
                   value="{{ old('name') }}"
                   placeholder="Full name" required>
          </div>
        </div>

        {{-- Email only --}}
        <div class="form-group">
          <label class="form-label">Email Address <span class="req">*</span></label>
          <div class="iw">
            <i class="bi bi-envelope il"></i>
            <input type="email" class="form-control" name="email"
                   value="{{ old('email') }}"
                   placeholder="user@example.com" required>
          </div>
        </div>

        {{-- Role only --}}
        <div class="form-group">
          <label class="form-label">Role <span class="req">*</span></label>
          <select class="form-select" name="role" required>
            <option value="">— Select role —</option>
            @foreach($roles ?? ['Admin','Auditor','Reviewer','Viewer'] as $r)
              <option value="{{ is_object($r) ? $r->name : $r }}"
                @selected(old('role') === (is_object($r) ? $r->name : $r))>
                {{ is_object($r) ? $r->name : $r }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Password --}}
        <div style="padding:16px;background:#f8fafc;border-radius:10px;border:1px solid var(--card-border);margin-bottom:16px">
          <div style="font-size:12px;font-weight:700;color:var(--t2);letter-spacing:.4px;text-transform:uppercase;margin-bottom:12px;display:flex;align-items:center;gap:6px">
            <i class="bi bi-lock"></i> Password Setup
          </div>
          <div class="grid-2" style="margin-bottom:0">
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label">Password <span class="req">*</span></label>
              <div class="iw" x-data="{ sp: false }">
                <i class="bi bi-lock il"></i>
                <input :type="sp ? 'text' : 'password'"
                       class="form-control" name="password"
                       placeholder="Min. 8 characters"
                       id="passwordInput" required
                       oninput="checkStrength(this.value)">
                <button type="button"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;color:var(--t3);cursor:pointer;font-size:15px;padding:2px"
                        @click="sp = !sp">
                  <i class="bi" :class="sp ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
              </div>
              {{-- Strength bar --}}
              <div style="margin-top:6px">
                <div style="height:4px;background:#f1f5f9;border-radius:10px;overflow:hidden">
                  <div id="strengthBar" style="height:100%;width:0;border-radius:10px;transition:all .25s;background:var(--c-red)"></div>
                </div>
                <div id="strengthLabel" style="font-size:10.5px;color:var(--t3);margin-top:3px"></div>
              </div>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label class="form-label">Confirm Password <span class="req">*</span></label>
              <div class="iw" x-data="{ sp2: false }">
                <i class="bi bi-lock-fill il"></i>
                <input :type="sp2 ? 'text' : 'password'"
                       class="form-control" name="password_confirmation"
                       placeholder="Repeat password" required
                       id="confirmInput" oninput="checkMatch()">
                <button type="button"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;color:var(--t3);cursor:pointer;font-size:15px;padding:2px"
                        @click="sp2 = !sp2">
                  <i class="bi" :class="sp2 ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
              </div>
              <div id="matchMsg" style="font-size:10.5px;margin-top:3px"></div>
            </div>
          </div>
        </div>

        {{-- Status toggle --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:#f8fafc;border-radius:9px;border:1px solid var(--card-border);margin-bottom:20px">
          <div>
            <div style="font-size:13px;font-weight:600;color:var(--t1)">Account Active</div>
            <div style="font-size:12px;color:var(--t3)">User can login immediately after creation</div>
          </div>
          <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer">
            <input type="checkbox" name="is_active" value="1" checked
                   style="position:absolute;opacity:0;width:0;height:0" id="activeToggle"
                   onchange="this.checked ? document.getElementById('toggleTrack').style.background='#16a34a' : document.getElementById('toggleTrack').style.background='#e2e8f4'; document.getElementById('toggleThumb').style.left=this.checked?'22px':'2px'">
            <div id="toggleTrack" style="width:44px;height:24px;border-radius:12px;background:#16a34a;transition:background .2s;position:relative">
              <div id="toggleThumb" style="position:absolute;top:2px;left:22px;width:20px;height:20px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 4px rgba(0,0,0,.2)"></div>
            </div>
          </label>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Create User
          </button>
          <a href="{{ route('users.index') }}" class="btn btn-outline">
            <i class="bi bi-x-lg"></i> Cancel
          </a>
        </div>

      </form>
    </div>
  </div>

  {{-- ═══ SIDEBAR ═══ --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Role guide --}}
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-shield-lock" style="color:var(--c-purple)"></i>Role Reference</span></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
        @foreach([
          ['Admin','purple','Full system access — manage users, roles, menus, all data'],
          ['Auditor','blue','Create and manage audits, fieldwork, findings, follow-up'],
          ['Reviewer','green','Read and review audit reports, approve findings'],
          ['Viewer','slate','Read-only access to reports and dashboards'],
        ] as [$role,$color,$desc])
        <div style="display:flex;gap:10px;align-items:flex-start">
          <span class="badge b-{{ $color }}" style="flex-shrink:0;margin-top:1px">{{ $role }}</span>
          <span style="font-size:12px;color:var(--t2);line-height:1.5">{{ $desc }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Tips --}}
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-lightbulb" style="color:var(--c-amber)"></i>Tips</span></div>
      <div class="card-body" style="font-size:12.5px;color:var(--t2);line-height:1.75;display:flex;flex-direction:column;gap:8px">
        <div><i class="bi bi-check2" style="color:var(--c-green);margin-right:5px"></i>Use a real email — it's used for password reset</div>
        <div><i class="bi bi-check2" style="color:var(--c-green);margin-right:5px"></i>Password must be at least 8 characters</div>
        <div><i class="bi bi-check2" style="color:var(--c-green);margin-right:5px"></i>Role controls what the user can see and do</div>
        <div><i class="bi bi-check2" style="color:var(--c-green);margin-right:5px"></i>Deactivating prevents login without deleting data</div>
      </div>
    </div>

  </div>

</div>

<script>
function checkStrength(val) {
  var bar = document.getElementById('strengthBar');
  var lbl = document.getElementById('strengthLabel');
  var score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  var levels = [
    {w:'0%',c:'#e2e8f4',t:''},
    {w:'25%',c:'#dc2626',t:'Weak'},
    {w:'50%',c:'#d97706',t:'Fair'},
    {w:'75%',c:'#2563eb',t:'Good'},
    {w:'100%',c:'#16a34a',t:'Strong'},
  ];
  var l = levels[score];
  bar.style.width = l.w;
  bar.style.background = l.c;
  lbl.textContent = l.t;
  lbl.style.color = l.c;
}

function checkMatch() {
  var pw = document.getElementById('passwordInput').value;
  var cf = document.getElementById('confirmInput').value;
  var msg = document.getElementById('matchMsg');
  if (!cf) { msg.textContent = ''; return; }
  if (pw === cf) {
    msg.textContent = '✓ Passwords match';
    msg.style.color = '#16a34a';
  } else {
    msg.textContent = '✗ Passwords do not match';
    msg.style.color = '#dc2626';
  }
}
</script>

@endsection
