<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('page-title','Dashboard') — Audit System</title>
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<script defer src="{{ asset('js/alpine.min.js') }}"></script>
<style>
/* ─────────────────────────────────────────────────────────
   DESIGN TOKENS  (single source of truth for every page)
───────────────────────────────────────────────────────── */
:root{
  --sb-w:258px; --sb-mini:64px; --tb-h:58px;
  --sb-bg:#0d2137; --sb-bg2:#08182b;
  --sb-accent:#f59e0b;
  --sb-txt:rgba(255,255,255,.78); --sb-muted:rgba(255,255,255,.34);
  --sb-hover:rgba(255,255,255,.07); --sb-active:rgba(255,255,255,.14);
  --sb-line:rgba(255,255,255,.08);

  --page-bg:#eef1f7;
  --card:#fff; --card-border:#e2e8f4;
  --r:11px; --r-sm:8px; --r-xs:6px;
  --shadow:0 1px 3px rgba(0,0,0,.04),0 4px 16px rgba(0,0,0,.06);

  --t1:#0c1829; --t2:#52637a; --t3:#96a8be;

  --c-green:#16a34a; --c-green-bg:#dcfce7;
  --c-amber:#d97706; --c-amber-bg:#fef3c7;
  --c-red:#dc2626;   --c-red-bg:#fee2e2;
  --c-blue:#2563eb;  --c-blue-bg:#dbeafe;
  --c-purple:#7c3aed;--c-purple-bg:#ede9fe;
  --c-slate:#475569; --c-slate-bg:#f1f5f9;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;background:var(--page-bg);color:var(--t1);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
[x-cloak]{display:none!important}

/* ── SIDEBAR ───────────────────────────────────────── */
.sb{
  position:fixed;top:0;left:0;width:var(--sb-w);height:100vh;
  background:linear-gradient(170deg,var(--sb-bg) 0%,var(--sb-bg2) 100%);
  display:flex;flex-direction:column;
  transition:width .25s cubic-bezier(.4,0,.2,1);
  z-index:1000;overflow:hidden;
  box-shadow:4px 0 20px rgba(0,0,0,.18);
}
.sb.mini{width:var(--sb-mini)}

/* brand */
.sb-brand{
  height:var(--tb-h);display:flex;align-items:center;
  gap:11px;padding:0 14px;flex-shrink:0;
  border-bottom:1px solid var(--sb-line);
}
.sb-logo{
  width:220px;height:30px;border-radius:9px;flex-shrink:0;
  overflow:hidden;
  display:flex;align-items:center;justify-content:center;
  font-weight:800;font-size:15px;color:#fff;
}
.sb-logo img{width:100%;height:100%;object-fit:cover}
.sb-name{overflow:hidden;white-space:nowrap;transition:opacity .2s}
.sb-name strong{display:block;font-size:13.5px;font-weight:700;color:#fff;letter-spacing:-.2px}
.sb-name small{font-size:10px;color:var(--sb-muted);letter-spacing:.5px;text-transform:uppercase}
.sb.mini .sb-name{opacity:0;pointer-events:none}

/* scroll area */
.sb-scroll{flex:1;overflow-y:auto;overflow-x:hidden;padding:8px 0 12px}
.sb-scroll::-webkit-scrollbar{width:3px}
.sb-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:3px}

/* section label */
.sb-sec{
  font-size:10px;font-weight:700;letter-spacing:1.3px;text-transform:uppercase;
  color:var(--sb-muted);padding:12px 16px 4px;white-space:nowrap;
  transition:opacity .2s;
}
.sb.mini .sb-sec{opacity:0}

/* ── L1 nav item ─── */
.n1{position:relative;margin:1px 7px}
.n1-a{
  display:flex;align-items:center;gap:11px;
  padding:8px 10px;border-radius:9px;
  color:var(--sb-txt);cursor:pointer;user-select:none;
  transition:background .13s,color .13s;white-space:nowrap;
}
.n1-a:hover{background:var(--sb-hover);color:#fff}
.n1-a.open,.n1-a.cur{color:#fff}
.n1-a.cur{background:var(--sb-active)}
.n1-ico{font-size:16px;flex-shrink:0;width:22px;text-align:center}
.n1-lbl{flex:1;font-size:16px;font-weight:500;overflow:hidden;text-overflow:ellipsis;transition:opacity .2s}
.sb.mini .n1-lbl{opacity:0}
.n1-chev{font-size:14px;flex-shrink:0;color:var(--sb-muted);transition:transform .2s,opacity .2s}
.n1-a.open .n1-chev{transform:rotate(180deg);color:var(--sb-txt)}
.sb.mini .n1-chev{opacity:0}

/* mini tooltip */
.sb.mini .n1:hover::after{
  content:attr(data-tip);
  position:absolute;left:calc(var(--sb-mini) + 8px);top:50%;transform:translateY(-50%);
  background:#1b2d42;color:#fff;font-size:12px;font-weight:500;
  padding:5px 11px;border-radius:7px;white-space:nowrap;
  z-index:9999;box-shadow:0 4px 14px rgba(0,0,0,.3);pointer-events:none;
}

/* ── L2 submenu ─── */
.n2-wrap{margin:2px 0 2px 13px;border-left:1px solid var(--sb-line);overflow:hidden}
.sb.mini .n2-wrap{display:none}
.n2-a{
  display:flex;align-items:center;gap:9px;
  padding:6.5px 10px;border-radius:7px;margin:1px 5px;
  color:var(--sb-muted);font-size:14px;font-weight:400;
  cursor:pointer;user-select:none;
  transition:background .12s,color .12s;white-space:nowrap;
}
.n2-a:hover{background:var(--sb-hover);color:rgba(255,255,255,.78)}
.n2-a.cur{color:var(--sb-accent);font-weight:600;background:rgba(245,158,11,.08)}
.n2-a.open{color:rgba(255,255,255,.65)}
.n2-ico{font-size:14px;opacity:.55;flex-shrink:0}
.n2-lbl{flex:1}
.n2-chev{font-size:13px;color:var(--sb-muted);transition:transform .18s;flex-shrink:0}
.n2-a.open .n2-chev{transform:rotate(180deg)}

/* ── L3 submenu ─── */
.n3-wrap{margin:1px 0 1px 12px;border-left:1px dashed var(--sb-line)}
.n3-a{
  display:flex;align-items:center;gap:8px;
  padding:5px 9px;border-radius:6px;margin:1px 4px;
  color:var(--sb-muted);font-size:12px;
  transition:background .12s,color .12s;white-space:nowrap;
}
.n3-a:hover{background:var(--sb-hover);color:rgba(255,255,255,.62)}
.n3-a.open{color:rgba(255,255,255,.55)}
.n3-a.cur{color:var(--sb-accent);font-weight:600}
.n3-dot{width:4px;height:4px;border-radius:50%;background:currentColor;opacity:.5;flex-shrink:0}
.n3-chev{font-size:11px;color:var(--sb-muted);transition:transform .18s;flex-shrink:0;margin-left:auto}
.n3-a.open .n3-chev{transform:rotate(180deg)}

/* ── L4 submenu ─── */
.n4-wrap{margin:1px 0 1px 11px;border-left:1px dotted rgba(255,255,255,.12)}
.n4-a{
  display:flex;align-items:center;gap:7px;
  padding:4px 8px;border-radius:5px;margin:1px 3px;
  color:var(--sb-muted);font-size:11.5px;
  transition:background .12s,color .12s;white-space:nowrap;
}
.n4-a:hover{background:var(--sb-hover);color:rgba(255,255,255,.55)}
.n4-a.open{color:rgba(255,255,255,.45)}
.n4-a.cur{color:var(--sb-accent);font-weight:600}
.n4-dot{width:3px;height:3px;border-radius:50%;background:currentColor;opacity:.45;flex-shrink:0}
.n4-chev{font-size:10px;color:var(--sb-muted);transition:transform .18s;flex-shrink:0;margin-left:auto}
.n4-a.open .n4-chev{transform:rotate(180deg)}

/* ── L5 submenu ─── */
.n5-wrap{margin:1px 0 1px 10px;border-left:1px dotted rgba(255,255,255,.08)}
.n5-a{
  display:flex;align-items:center;gap:6px;
  padding:3.5px 7px;border-radius:5px;margin:1px 3px;
  color:rgba(255,255,255,.32);font-size:11px;
  transition:background .12s,color .12s;white-space:nowrap;
}
.n5-a:hover{background:var(--sb-hover);color:rgba(255,255,255,.5)}
.n5-a.cur{color:var(--sb-accent);font-weight:600}
.n5-dot{width:2px;height:2px;border-radius:50%;background:currentColor;opacity:.4;flex-shrink:0}

/* sidebar user footer */
.sb-foot{padding:9px 8px;border-top:1px solid var(--sb-line);flex-shrink:0}
.sb-user{display:flex;align-items:center;gap:9px;padding:7px 8px;border-radius:9px;background:var(--sb-hover)}
.sb-uav{
  width:30px;height:30px;border-radius:8px;flex-shrink:0;
  overflow:hidden;background:var(--sb-accent);
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:11px;color:#fff;
}
.sb-uav img{width:100%;height:100%;object-fit:cover}
.sb-uname{font-size:12px;font-weight:600;color:rgba(255,255,255,.8);white-space:nowrap;overflow:hidden;transition:opacity .2s}
.sb.mini .sb-uname{opacity:0}

/* ── TOPBAR ────────────────────────────────────────── */
.tb{
  position:fixed;top:0;left:var(--sb-w);right:0;height:var(--tb-h);
  background:var(--card);border-bottom:1px solid var(--card-border);
  display:flex;align-items:center;justify-content:space-between;
  padding:0 20px 0 16px;
  transition:left .25s cubic-bezier(.4,0,.2,1);z-index:999;
}
.tb.mini{left:var(--sb-mini)}
.tb-l,.tb-r{display:flex;align-items:center;gap:4px}
.tb-tog{
  border:none;background:none;width:36px;height:36px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  color:var(--t2);cursor:pointer;font-size:20px;transition:background .12s,color .12s;
}
.tb-tog:hover{background:#f1f5f9;color:var(--t1)}
.tb-bread{display:flex;align-items:center;gap:5px;font-size:12.5px;color:var(--t3);margin-left:6px}
.tb-bread .sep{font-size:9px}
.tb-bread .cur{color:var(--t1);font-weight:600}
.tb-btn{
  position:relative;border:none;background:none;
  width:36px;height:36px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  color:var(--t2);cursor:pointer;font-size:17px;transition:background .12s,color .12s;
}
.tb-btn:hover{background:#f1f5f9;color:var(--t1)}
.tb-dot{position:absolute;top:7px;right:7px;width:7px;height:7px;border-radius:50%;background:var(--c-red);border:2px solid #fff}
.tb-div{width:1px;height:22px;background:var(--card-border);margin:0 6px}
.tb-prof{
  display:flex;align-items:center;gap:8px;padding:4px 8px 4px 4px;
  border-radius:9px;border:none;background:none;cursor:pointer;
  transition:background .12s;position:relative;
}
.tb-prof:hover{background:#f1f5f9}
.tb-av{
  width:32px;height:32px;border-radius:8px;flex-shrink:0;
  overflow:hidden;background:linear-gradient(135deg,#2563eb,#0d2137);
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:12px;color:#fff;
}
.tb-av img{width:100%;height:100%;object-fit:cover}
.tb-pname{font-size:13px;font-weight:600;color:var(--t1);line-height:1.25;text-align:left}
.tb-prole{font-size:10.5px;color:var(--t3);line-height:1.25;text-align:left}
.tb-drop{
  position:absolute;top:calc(100% + 8px);right:0;
  background:var(--card);border:1px solid var(--card-border);
  border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.12);
  width:210px;overflow:hidden;z-index:1100;
}
.tb-drop-hd{padding:13px 15px 10px;border-bottom:1px solid #f1f5f9}
.tb-drop-hd .dn{font-weight:600;font-size:13px;color:var(--t1)}
.tb-drop-hd .de{font-size:11px;color:var(--t3);margin-top:1px}
.ddi{
  display:flex;align-items:center;gap:9px;padding:8px 15px;
  font-size:13px;color:#374151;width:100%;border:none;background:none;cursor:pointer;text-align:left;
  transition:background .1s;
}
.ddi:hover{background:#f8fafc}
.ddi i{font-size:14px;color:var(--t3)}
.ddi.red{color:var(--c-red)}.ddi.red i{color:var(--c-red)}.ddi.red:hover{background:#fef2f2}
.dd-sep{height:1px;background:#f1f5f9;margin:3px 0}

/* ── MAIN ──────────────────────────────────────────── */
.main{
  margin-left:var(--sb-w);margin-top:var(--tb-h);
  padding:22px 24px;min-height:calc(100vh - var(--tb-h));
  transition:margin-left .25s cubic-bezier(.4,0,.2,1);
}
.main.mini{margin-left:var(--sb-mini)}

/* ══════════════════════════════════════════════════════
   SHARED COMPONENT LIBRARY  — used on every child view
══════════════════════════════════════════════════════ */

/* Page header */
.ph{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px}
.ph-info h1{font-size:20px;font-weight:700;color:var(--t1);letter-spacing:-.3px;line-height:1.2}
.ph-info p{font-size:13px;color:var(--t3);margin-top:3px}
.ph-acts{display:flex;gap:8px;flex-wrap:wrap;align-items:center}

/* Cards */
.card{background:var(--card);border-radius:var(--r);border:1px solid var(--card-border);box-shadow:var(--shadow)}
.card-hd{
  padding:14px 18px;border-bottom:1px solid var(--card-border);
  display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;
}
.card-title{font-size:13.5px;font-weight:600;color:var(--t1);display:flex;align-items:center;gap:7px}
.card-title i{font-size:15px}
.card-body{padding:18px}

/* Stat cards */
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:13px;margin-bottom:18px}
.stat-card{
  background:var(--card);border-radius:var(--r);border:1px solid var(--card-border);
  box-shadow:var(--shadow);padding:15px 17px;display:flex;align-items:flex-start;gap:12px;
}
.stat-ico{width:40px;height:40px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:18px}
.ico-blue{background:var(--c-blue-bg);color:var(--c-blue)}
.ico-green{background:var(--c-green-bg);color:var(--c-green)}
.ico-amber{background:var(--c-amber-bg);color:var(--c-amber)}
.ico-red{background:var(--c-red-bg);color:var(--c-red)}
.ico-purple{background:var(--c-purple-bg);color:var(--c-purple)}
.ico-slate{background:var(--c-slate-bg);color:var(--c-slate)}
.stat-val{font-size:22px;font-weight:700;color:var(--t1);letter-spacing:-.5px;line-height:1}
.stat-lbl{font-size:12px;color:var(--t3);margin-top:3px}
.stat-sub{font-size:11px;margin-top:5px;font-weight:500}
.stat-sub.up{color:var(--c-green)}.stat-sub.dn{color:var(--c-red)}

/* Table */
.tbl{width:100%;border-collapse:collapse}
.tbl th{
  padding:10px 14px;text-align:left;font-size:10.5px;font-weight:700;
  letter-spacing:.6px;text-transform:uppercase;color:var(--t2);
  background:#f8fafc;border-bottom:1px solid var(--card-border);
}
.tbl td{padding:11px 14px;border-bottom:1px solid #f1f5f9;font-size:13px;color:var(--t1);vertical-align:middle}
.tbl tbody tr:last-child td{border-bottom:none}
.tbl tbody tr:hover td{background:#fafbff}

/* Badges */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.b-green{background:var(--c-green-bg);color:var(--c-green)}
.b-amber{background:var(--c-amber-bg);color:var(--c-amber)}
.b-red{background:var(--c-red-bg);color:var(--c-red)}
.b-blue{background:var(--c-blue-bg);color:var(--c-blue)}
.b-purple{background:var(--c-purple-bg);color:var(--c-purple)}
.b-slate{background:var(--c-slate-bg);color:var(--c-slate)}

/* Buttons */
.btn{
  display:inline-flex;align-items:center;gap:6px;
  padding:7px 14px;border-radius:var(--r-sm);font-size:13px;font-weight:500;
  border:1px solid transparent;cursor:pointer;
  transition:all .12s;font-family:inherit;white-space:nowrap;
}
.btn-primary{background:#0d2137;color:#fff;border-color:#0d2137}.btn-primary:hover{background:#163554}
.btn-success{background:var(--c-green);color:#fff}.btn-success:hover{background:#15803d}
.btn-danger{background:var(--c-red);color:#fff}.btn-danger:hover{background:#b91c1c}
.btn-outline{background:#fff;color:var(--t2);border-color:var(--card-border)}.btn-outline:hover{background:#f8fafc;color:var(--t1)}
.btn-sm{padding:4px 10px;font-size:12px;border-radius:var(--r-xs)}
.btn:disabled{opacity:.55;cursor:not-allowed}

/* Forms */
.form-group{margin-bottom:16px}
.form-label{display:block;font-size:11px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:var(--t2);margin-bottom:5px}
.req{color:var(--c-red)}
.form-control,.form-select{
  width:100%;height:40px;padding:0 12px;
  border:1.5px solid var(--card-border);border-radius:var(--r-sm);
  font-family:inherit;font-size:13px;color:var(--t1);
  background:#fafbfc;transition:border-color .14s,box-shadow .14s;outline:none;
}
textarea.form-control{height:auto;padding:9px 12px;resize:vertical}
.form-control::placeholder,.form-select::placeholder{color:var(--t3)}
.form-control:focus,.form-select:focus{border-color:#0d2137;background:#fff;box-shadow:0 0 0 3px rgba(13,33,55,.09)}
.form-control:hover:not(:focus),.form-select:hover:not(:focus){border-color:#c0cde0}
.form-select{
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2396a8be' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 11px center;padding-right:30px;
}
.form-hint{font-size:11px;color:var(--t3);margin-top:4px}
.iw{position:relative}
.iw .il{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--t3);font-size:14px;pointer-events:none}
.iw .form-control{padding-left:34px}

/* Alerts */
.alert{padding:11px 14px;border-radius:9px;font-size:13px;display:flex;align-items:center;gap:8px;margin-bottom:14px}
.alert-err{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c}
.alert-ok{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d}
.alert-warn{background:#fffbeb;border:1px solid #fde68a;color:#92400e}

/* Two-col grid helper */
.grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.col-span-2{grid-column:span 2}
</style>
</head>

<body x-data="{ mini:false }">
@php
  $user  = auth()->user();
  $uinit = collect(explode(' ', $user->name ?? 'U'))
             ->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
@endphp

{{-- ═══ SIDEBAR ═══════════════════════════════════ --}}
<aside :class="mini ? 'sb mini' : 'sb'">

  {{-- Brand --}}
  <div class="sb-brand">
    <div class="sb-logo">
      <img src="{{ asset('images/logo.png') }}" alt="AS"
           onerror="this.style.display='none';this.parentNode.textContent='AS'">
    </div>
  </div>

  {{-- Navigation --}}
  <div class="sb-scroll">
    <div class="sb-sec">Main</div>

    @foreach($menus as $menu)
      @php $hasL2 = $menu->children->count() > 0; @endphp

      <div class="n1" data-tip="{{ $menu->name }}" x-data="{ o1:false }">

        {{-- L1 --}}
        @if($hasL2)
          <div class="n1-a" :class="{open:o1}" @click="o1 = !o1">
            <i class="bi {{ $menu->icon ?? 'bi-grid' }} n1-ico"></i>
            <span class="n1-lbl">{{ $menu->name }}</span>
            <i class="bi bi-chevron-down n1-chev"></i>
          </div>
        @else
          <a href="{{ url($menu->route ?? '#') }}"
             class="n1-a {{ request()->is(ltrim($menu->route??'','/').'*') ? 'cur':'' }}">
            <i class="bi {{ $menu->icon ?? 'bi-grid' }} n1-ico"></i>
            <span class="n1-lbl">{{ $menu->name }}</span>
          </a>
        @endif

        @if($hasL2)
        <div class="n2-wrap" x-show="o1" x-transition x-cloak>
          @foreach($menu->children as $l2)
            @php $hasL3 = $l2->children->count() > 0; @endphp
            <div x-data="{ o2:false }">

              @if($hasL3)
                {{-- L2 with children --}}
                <div class="n2-a" :class="{open:o2}" @click="o2 = !o2">
                  <i class="bi bi-chevron-right n2-ico"></i>
                  <span class="n2-lbl">{{ $l2->name }}</span>
                  <i class="bi bi-chevron-down n2-chev"></i>
                </div>
                <div class="n3-wrap" x-show="o2" x-transition x-cloak>
                  @foreach($l2->children as $l3)
                    @php $hasL4 = $l3->children->count() > 0; @endphp
                    <div x-data="{ o3:false }">

                      @if($hasL4)
                        {{-- L3 with children --}}
                        <div class="n3-a" :class="{open:o3}" @click="o3 = !o3" style="cursor:pointer">
                          <span class="n3-dot"></span>
                          <span style="flex:1">{{ $l3->name }}</span>
                          <i class="bi bi-chevron-down n3-chev"></i>
                        </div>
                        <div class="n4-wrap" x-show="o3" x-transition x-cloak>
                          @foreach($l3->children as $l4)
                            @php $hasL5 = $l4->children->count() > 0; @endphp
                            <div x-data="{ o4:false }">

                              @if($hasL5)
                                {{-- L4 with children --}}
                                <div class="n4-a" :class="{open:o4}" @click="o4 = !o4" style="cursor:pointer">
                                  <span class="n4-dot"></span>
                                  <span style="flex:1">{{ $l4->name }}</span>
                                  <i class="bi bi-chevron-down n4-chev"></i>
                                </div>
                                <div class="n5-wrap" x-show="o4" x-transition x-cloak>
                                  @foreach($l4->children as $l5)
                                    <a href="{{ url($l5->route ?? '#') }}"
                                       class="n5-a {{ request()->is(ltrim($l5->route??'','/').'*') ? 'cur':'' }}">
                                      <span class="n5-dot"></span>{{ $l5->name }}
                                    </a>
                                  @endforeach
                                </div>
                              @else
                                {{-- L4 plain link --}}
                                <a href="{{ url($l4->route ?? '#') }}"
                                   class="n4-a {{ request()->is(ltrim($l4->route??'','/').'*') ? 'cur':'' }}">
                                  <span class="n4-dot"></span>{{ $l4->name }}
                                </a>
                              @endif

                            </div>
                          @endforeach
                        </div>
                      @else
                        {{-- L3 plain link --}}
                        <a href="{{ url($l3->route ?? '#') }}"
                           class="n3-a {{ request()->is(ltrim($l3->route??'','/').'*') ? 'cur':'' }}">
                          <span class="n3-dot"></span>{{ $l3->name }}
                        </a>
                      @endif

                    </div>
                  @endforeach
                </div>

              @else
                {{-- L2 plain link --}}
                <div class="n2-a {{ request()->is(ltrim($l2->route??'','/').'*') ? 'cur':'' }}">
                  <a href="{{ url($l2->route ?? '#') }}"
                     style="display:flex;align-items:center;gap:9px;flex:1;color:inherit">
                    <i class="bi bi-chevron-right n2-ico"></i>
                    <span class="n2-lbl">{{ $l2->name }}</span>
                  </a>
                </div>
              @endif

            </div>
          @endforeach
        </div>
        @endif

      </div>
    @endforeach

    <div class="sb-sec" style="margin-top:8px">System</div>
    <div class="n1" data-tip="Settings">
      <a href="#" class="n1-a"><i class="bi bi-gear n1-ico"></i><span class="n1-lbl">Settings</span></a>
    </div>
    <div class="n1" data-tip="Help &amp; Support">
      <a href="#" class="n1-a"><i class="bi bi-question-circle n1-ico"></i><span class="n1-lbl">Help &amp; Support</span></a>
    </div>
  </div>
</aside>

{{-- ═══ TOPBAR ══════════════════════════════════════ --}}
<header :class="mini ? 'tb mini' : 'tb'">
  <div class="tb-l">
    <button class="tb-tog" @click="mini = !mini" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>
    <nav class="tb-bread">
      <i class="bi bi-house" style="font-size:13px"></i>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="cur">@yield('page-title','Dashboard')</span>
    </nav>
  </div>

  <div class="tb-r">
    <button class="tb-btn"><i class="bi bi-bell"></i><span class="tb-dot"></span></button>
    <div class="tb-div"></div>

    <div x-data="{ open:false }" style="position:relative">
      <button class="tb-prof" @click="open = !open">
        <div class="tb-av">

        </div>
        <div>
          <div class="tb-pname">{{ $user->name ?? 'Guest' }}</div>
          <div class="tb-prole">{{ $user->roles->first()->name ?? 'User' }}</div>
        </div>
        <i class="bi bi-chevron-down" style="font-size:10px;color:var(--t3);margin-left:3px"></i>
      </button>

      <div class="tb-drop" x-show="open" @click.outside="open=false" x-transition x-cloak>
        <div class="tb-drop-hd">
          <div class="dn">{{ $user->name ?? 'Guest' }}</div>
          <div class="de">{{ $user->email ?? '' }}</div>
        </div>
        <a href="#" class="ddi"><i class="bi bi-person"></i> My Profile</a>
        <a href="#" class="ddi"><i class="bi bi-shield-lock"></i> Security</a>
        <div class="dd-sep"></div>
        <form method="POST" action="{{ route('logout') }}">@csrf
          <button type="submit" class="ddi red"><i class="bi bi-box-arrow-right"></i> Sign Out</button>
        </form>
      </div>
    </div>
  </div>
</header>

{{-- ═══ MAIN ════════════════════════════════════════ --}}
<main :class="mini ? 'main mini' : 'main'">
  @yield('content')
</main>

</body>
</html>
