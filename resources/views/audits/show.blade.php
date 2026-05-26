@extends('layouts.app')
@section('page-title', $audit->title)
@section('content')

<div class="ph">
  <div class="ph-info">
    <h1>{{ $audit->title }}</h1>
    <p>Audit Detail — Fieldwork &amp; Findings</p>
  </div>
  <div class="ph-acts">
    <a href="{{ route('audits.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
    <span class="badge {{ $audit->status=='Completed'?'b-green':($audit->status=='In Progress'?'b-blue':'b-slate') }}"
          style="padding:6px 14px;font-size:12px">{{ $audit->status }}</span>
  </div>
</div>

{{-- Summary strip --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat-card">
    <div class="stat-ico ico-red"><i class="bi bi-shield-exclamation"></i></div>
    <div><div class="stat-lbl">Risk Level</div><div style="font-size:16px;font-weight:700;margin-top:3px">{{ $audit->risk_level }}</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-amber"><i class="bi bi-calendar-event"></i></div>
    <div><div class="stat-lbl">Start Date</div><div style="font-size:14px;font-weight:600;margin-top:3px">{{ $audit->planned_start_date }}</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-green"><i class="bi bi-calendar-check"></i></div>
    <div><div class="stat-lbl">End Date</div><div style="font-size:14px;font-weight:600;margin-top:3px">{{ $audit->planned_end_date }}</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-purple"><i class="bi bi-flag"></i></div>
    <div><div class="stat-lbl">Findings</div><div class="stat-val">{{ $audit->findings->count() }}</div></div>
  </div>
</div>

@if($audit->scope)
<div class="card" style="margin-bottom:14px">
  <div class="card-hd"><span class="card-title"><i class="bi bi-text-paragraph" style="color:var(--c-blue)"></i>Scope</span></div>
  <div class="card-body" style="color:var(--t2);line-height:1.75">{{ $audit->scope }}</div>
</div>
@endif

{{-- Add Fieldwork + Add Finding (side by side) --}}
<div class="grid-2" style="margin-bottom:14px">

  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-journal-check" style="color:var(--c-purple)"></i>Add Fieldwork</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('fieldwork.store', $audit) }}" enctype="multipart/form-data">@csrf
        <div class="form-group">
          <label class="form-label">Checklist Item</label>
          <input class="form-control" name="checklist_item" placeholder="Checklist item">
        </div>
        <div class="form-group">
          <label class="form-label">Working Paper</label>
          <textarea class="form-control" name="working_paper" rows="2" placeholder="Working paper notes…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Audit Notes</label>
          <textarea class="form-control" name="audit_notes" rows="2" placeholder="Audit notes…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Evidence File</label>
          <input type="file" class="form-control" name="evidence_file">
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <option>Pending</option><option>Completed</option>
          </select>
        </div>
        <button class="btn btn-success"><i class="bi bi-plus-lg"></i> Add Fieldwork</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-exclamation-octagon" style="color:var(--c-red)"></i>Add Finding</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('findings.store', $audit) }}">@csrf
        <div class="form-group">
          <label class="form-label">Finding Title</label>
          <input class="form-control" name="title" placeholder="Finding title">
        </div>
        <div class="form-group">
          <label class="form-label">Risk Rating</label>
          <select class="form-select" name="risk_rating">
            <option>Low</option><option>Medium</option><option>High</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Root Cause</label>
          <textarea class="form-control" name="root_cause" rows="2" placeholder="Root cause analysis…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Recommendation</label>
          <textarea class="form-control" name="recommendation" rows="2" placeholder="Recommendation…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Management Response</label>
          <textarea class="form-control" name="management_response" rows="2" placeholder="Management response…"></textarea>
        </div>
        <button class="btn btn-danger"><i class="bi bi-flag"></i> Add Finding</button>
      </form>
    </div>
  </div>

</div>

{{-- Findings list --}}
<div class="card">
  <div class="card-hd">
    <span class="card-title"><i class="bi bi-list-ul" style="color:var(--c-amber)"></i>Findings &amp; Follow-Up</span>
    <span class="badge b-slate">{{ $audit->findings->count() }} findings</span>
  </div>
  @forelse($audit->findings as $finding)
  <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
      <span style="font-weight:600;color:var(--t1)">{{ $finding->title }}</span>
      <span class="badge {{ $finding->risk_rating=='High'?'b-red':($finding->risk_rating=='Medium'?'b-amber':'b-green') }}">
        {{ $finding->risk_rating }}
      </span>
    </div>
    @if($finding->recommendation)
    <p style="font-size:13px;color:var(--t2);margin-bottom:12px;line-height:1.6">{{ $finding->recommendation }}</p>
    @endif
    <form method="POST" action="{{ route('followups.store', $finding) }}">@csrf
      <div class="grid-3" style="margin-bottom:8px">
        <div>
          <label class="form-label">Rec. Status</label>
          <input class="form-control" name="recommendation_status" placeholder="Status" style="height:36px">
        </div>
        <div>
          <label class="form-label">Due Date</label>
          <input type="date" class="form-control" name="due_date" style="height:36px">
        </div>
        <div>
          <label class="form-label">Follow-up Status</label>
          <select class="form-select" name="status" style="height:36px">
            <option>Open</option><option>Closed</option>
          </select>
        </div>
      </div>
      <div style="display:flex;gap:8px;align-items:flex-end">
        <div style="flex:1">
          <label class="form-label">Follow-up Comments</label>
          <input class="form-control" name="follow_up_comments" placeholder="Comments…" style="height:36px">
        </div>
        <button class="btn btn-outline btn-sm" style="height:36px"><i class="bi bi-save"></i> Save</button>
      </div>
    </form>
  </div>
  @empty
  <div style="padding:36px;text-align:center;color:var(--t3)">
    <i class="bi bi-inbox" style="font-size:26px;display:block;margin-bottom:8px;opacity:.4"></i>
    No findings recorded yet.
  </div>
  @endforelse
</div>

@endsection
