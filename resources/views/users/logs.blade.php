@extends('layouts.app')
@section('page-title','Activity Logs')
@section('content')
<div class="ph">
  <div class="ph-info"><h1>User Activity Logs</h1><p>Full audit trail of system activity</p></div>
  <div class="ph-acts">
    <button class="btn btn-outline"><i class="bi bi-download"></i> Export</button>
  </div>
</div>
<div class="card">
  <div class="card-hd">
    <span class="card-title"><i class="bi bi-clock-history" style="color:var(--c-purple)"></i>All Activity</span>
    <div class="iw" style="width:200px"><i class="bi bi-search il"></i><input class="form-control" placeholder="Search…" style="height:34px"></div>
  </div>
  <div style="overflow-x:auto">
    <table class="tbl">
      <thead><tr><th>User</th><th>Activity</th><th>IP Address</th><th>Date &amp; Time</th></tr></thead>
      <tbody>
        @forelse($logs as $log)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="width:28px;height:28px;border-radius:7px;background:var(--c-blue-bg);display:flex;align-items:center;justify-content:center;color:var(--c-blue);font-weight:700;font-size:11px">
                {{ strtoupper(substr($log->user->name??'?',0,1)) }}
              </div>
              <span style="font-weight:500">{{ $log->user->name??'-' }}</span>
            </div>
          </td>
          <td style="color:var(--t2)">{{ $log->activity }}</td>
          <td><span class="badge b-slate">{{ $log->ip_address }}</span></td>
          <td style="color:var(--t3);font-size:12.5px">{{ $log->created_at }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:var(--t3);padding:40px">No activity logs found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
