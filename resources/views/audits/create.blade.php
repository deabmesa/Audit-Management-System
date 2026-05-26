@extends('layouts.app')
@section('page-title','New Audit Engagement')
@section('content')

<div class="ph">
  <div class="ph-info">
    <h1>New Audit Engagement</h1>
    <p>Fill in the details to create a new audit</p>
  </div>
  <div class="ph-acts">
    <a href="{{ route('audits.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">

  <div class="card">
    <div class="card-hd">
      <span class="card-title"><i class="bi bi-clipboard2-plus" style="color:var(--c-blue)"></i>Engagement Details</span>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('audits.store') }}">@csrf

        <div class="form-group">
          <label class="form-label">Audit Title <span class="req">*</span></label>
          <input class="form-control" name="title" placeholder="Enter audit title" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Scope</label>
          <textarea class="form-control" name="scope" rows="3" placeholder="Describe the scope of this audit…">{{ old('scope') }}</textarea>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Risk Level <span class="req">*</span></label>
            <select class="form-select" name="risk_level" required>
              <option value="">Select risk level</option>
              <option {{ old('risk_level')=='Low'?'selected':'' }}>Low</option>
              <option {{ old('risk_level')=='Medium'?'selected':'' }}>Medium</option>
              <option {{ old('risk_level')=='High'?'selected':'' }}>High</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status <span class="req">*</span></label>
            <select class="form-select" name="status" required>
              <option {{ old('status')=='Planned'?'selected':'' }}>Planned</option>
              <option {{ old('status')=='In Progress'?'selected':'' }}>In Progress</option>
              <option {{ old('status')=='Completed'?'selected':'' }}>Completed</option>
            </select>
          </div>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Planned Start Date</label>
            <input type="date" class="form-control" name="planned_start_date" value="{{ old('planned_start_date') }}">
          </div>
          <div class="form-group">
            <label class="form-label">Planned End Date</label>
            <input type="date" class="form-control" name="planned_end_date" value="{{ old('planned_end_date') }}">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Assign Auditors</label>
          <select name="auditor_ids[]" multiple class="form-select" style="height:100px">
            @foreach($auditors as $a)
            <option value="{{ $a->id }}">{{ $a->name }}</option>
            @endforeach
          </select>
          <div class="form-hint">Hold Ctrl / Cmd to select multiple</div>
        </div>

        <div style="display:flex;gap:8px;margin-top:4px">
          <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Engagement</button>
          <a href="{{ route('audits.index') }}" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-info-circle" style="color:var(--c-blue)"></i>Guidance</span></div>
      <div class="card-body" style="font-size:13px;color:var(--t2);line-height:1.7">
        <p style="margin-bottom:8px">Fields marked <span class="req">*</span> are required.</p>
        <p style="margin-bottom:8px">Risk level determines finding prioritization and escalation rules.</p>
        <p>Fieldwork and findings can be added after saving.</p>
      </div>
    </div>
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-shield-exclamation" style="color:var(--c-amber)"></i>Risk Guide</span></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
        <div style="display:flex;align-items:center;gap:10px">
          <span class="badge b-red">High</span>
          <span style="font-size:12px;color:var(--t2)">Critical controls, significant exposure</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <span class="badge b-amber">Medium</span>
          <span style="font-size:12px;color:var(--t2)">Moderate risk, requires attention</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <span class="badge b-green">Low</span>
          <span style="font-size:12px;color:var(--t2)">Minor exposure, routine review</span>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
