@extends('layouts.app')
@section('page-title','Audit Engagements')
@section('content')

<div class="ph">
  <div class="ph-info">
    <h1>Audit Engagements</h1>
    <p>Manage and track all audit engagements</p>
  </div>
  <div class="ph-acts">
    <button class="btn btn-outline"><i class="bi bi-download"></i> Export</button>
    <a href="{{ route('audits.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Audit</a>
  </div>
</div>

<div class="card">
  <div class="card-hd">
    <span class="card-title"><i class="bi bi-clipboard2-check" style="color:var(--c-blue)"></i>All Engagements</span>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <div class="iw" style="width:200px">
        <i class="bi bi-search il"></i>
        <input class="form-control" placeholder="Search audits…" style="height:34px">
      </div>
      <select class="form-select" style="width:140px;height:34px">
        <option value="">All Status</option>
        <option>Planned</option>
        <option>In Progress</option>
        <option>Completed</option>
      </select>
      <select class="form-select" style="width:130px;height:34px">
        <option value="">All Risk</option>
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
      </select>
    </div>
  </div>

  <div style="overflow-x:auto">
    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Risk Level</th>
          <th>Status</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($audits as $i => $audit)
        <tr>
          <td style="color:var(--t3);font-size:12px">{{ $i + 1 }}</td>
          <td style="font-weight:500">{{ $audit->title }}</td>
          <td>
            <span class="badge {{ $audit->risk_level == 'High' ? 'b-red' : ($audit->risk_level == 'Medium' ? 'b-amber' : 'b-green') }}">
              {{ $audit->risk_level }}
            </span>
          </td>
          <td>
            <span class="badge {{ $audit->status == 'Completed' ? 'b-green' : ($audit->status == 'In Progress' ? 'b-blue' : 'b-slate') }}">
              {{ $audit->status }}
            </span>
          </td>
          <td style="color:var(--t2);font-size:12.5px">{{ $audit->planned_start_date }}</td>
          <td style="color:var(--t2);font-size:12.5px">{{ $audit->planned_end_date }}</td>
          <td style="text-align:right">
            <a href="{{ route('audits.show', $audit) }}" class="btn btn-outline btn-sm">
              <i class="bi bi-eye"></i> View
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;color:var(--t3);padding:40px 14px">
            <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;opacity:.4"></i>
            No audit engagements found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($audits, 'links'))
  <div style="padding:12px 18px;border-top:1px solid var(--card-border);display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:12px;color:var(--t3)">Showing {{ $audits->firstItem() }} – {{ $audits->lastItem() }} of {{ $audits->total() }}</span>
    {{ $audits->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
