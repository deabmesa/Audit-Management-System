@extends('layouts.app')
@section('page-title', isset($editMenu) ? 'Edit Menu' : 'Create Menu')
@section('content')

@php $isEdit = isset($editMenu) && $editMenu; @endphp

<div class="ph">
  <div class="ph-info">
    <h1>{{ $isEdit ? 'Edit Menu' : 'Create Menu' }}</h1>
    <p>{{ $isEdit ? 'Update menu item details' : 'Add a new item to the navigation structure' }}</p>
  </div>
  <div class="ph-acts">
    <a href="{{ route('menus.index') }}" class="btn btn-outline">
      <i class="bi bi-arrow-left"></i> Back to List Menus
    </a>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-err" style="margin-bottom:14px">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  </div>
@endif

<div style="display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start">

  {{-- ═══ MAIN FORM ═══ --}}
  <div class="card">
    <div class="card-hd">
      <span class="card-title">
        <i class="bi bi-{{ $isEdit ? 'pencil' : 'plus-circle' }}" style="color:var(--c-blue)"></i>
        Menu Details
      </span>
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ $isEdit ? route('menus.update', $editMenu->id) : route('menus.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Menu Name <span class="req">*</span></label>
            <input name="name" class="form-control"
                   value="{{ old('name', $editMenu->name ?? '') }}"
                   placeholder="e.g. Audit Management" required autofocus>
          </div>
          <div class="form-group">
            <label class="form-label">Display Order</label>
            <input name="order_no" type="number" min="0" class="form-control"
                   value="{{ old('order_no', $editMenu->order_no ?? 0) }}"
                   placeholder="0">
          </div>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Module <span class="req">*</span></label>
            <div class="iw">
              <i class="bi bi-folder2 il"></i>
              <input name="module" class="form-control"
                     value="{{ old('module', $editMenu->module ?? '') }}"
                     placeholder="e.g. audits" required>
            </div>
            <div class="form-hint">Controller folder name (lowercase)</div>
          </div>
          <div class="form-group">
            <label class="form-label">Page <span class="req">*</span></label>
            <div class="iw">
              <i class="bi bi-file-earmark il"></i>
              <input name="page" class="form-control"
                     value="{{ old('page', $editMenu->page ?? '') }}"
                     placeholder="e.g. index" required>
            </div>
            <div class="form-hint">View method (index, create, show…)</div>
          </div>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Icon</label>
            <div class="iw">
              <i class="bi bi-grid il" id="iconPreview"></i>
              <input name="icon" id="iconInput" class="form-control"
                     value="{{ old('icon', $editMenu->icon ?? '') }}"
                     placeholder="bi-house">
            </div>
            <div class="form-hint">
              Bootstrap Icons name — e.g. <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">bi-house</code>
              <a href="https://icons.getbootstrap.com" target="_blank" style="color:var(--c-blue);margin-left:4px;font-size:11px">Browse icons <i class="bi bi-box-arrow-up-right" style="font-size:9px"></i></a>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Permission Key</label>
            <div class="iw">
              <i class="bi bi-key il"></i>
              <input name="permission" class="form-control"
                     value="{{ old('permission', $editMenu->permission ?? '') }}"
                     placeholder="e.g. view_audits">
            </div>
            <div class="form-hint">Used by roles to control access. Leave blank for parent/group menus.</div>
          </div>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Parent Menu</label>
            <select name="parent_id" class="form-select">
              <option value="">— Top Level (no parent) —</option>
              @foreach($menus as $m)
                @if(!$isEdit || $m->id !== $editMenu->id)
                  <option value="{{ $m->id }}"
                    {{ old('parent_id', $editMenu->parent_id ?? '') == $m->id ? 'selected' : '' }}>
                    {{ $m->name }}
                  </option>
                  @if($m->children && $m->children->count())
                    @foreach($m->children as $c2)
                      @if(!$isEdit || $c2->id !== $editMenu->id)
                        <option value="{{ $c2->id }}"
                          {{ old('parent_id', $editMenu->parent_id ?? '') == $c2->id ? 'selected' : '' }}>
                          &nbsp;&nbsp;↳ {{ $c2->name }}
                        </option>
                        @if($c2->children && $c2->children->count())
                          @foreach($c2->children as $c3)
                            @if(!$isEdit || $c3->id !== $editMenu->id)
                              <option value="{{ $c3->id }}"
                                {{ old('parent_id', $editMenu->parent_id ?? '') == $c3->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $c3->name }}
                              </option>
                              @if($c3->children && $c3->children->count())
                                @foreach($c3->children as $c4)
                                  @if(!$isEdit || $c4->id !== $editMenu->id)
                                    <option value="{{ $c4->id }}"
                                      {{ old('parent_id', $editMenu->parent_id ?? '') == $c4->id ? 'selected' : '' }}>
                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $c4->name }}
                                    </option>
                                    @if($c4->children && $c4->children->count())
                                      @foreach($c4->children as $c5)
                                        @if(!$isEdit || $c5->id !== $editMenu->id)
                                          <option value="{{ $c5->id }}"
                                            {{ old('parent_id', $editMenu->parent_id ?? '') == $c5->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $c5->name }}
                                          </option>
                                        @endif
                                      @endforeach
                                    @endif
                                  @endif
                                @endforeach
                              @endif
                            @endif
                          @endforeach
                        @endif
                      @endif
                    @endforeach
                  @endif
                @endif
              @endforeach
            </select>
          </div>
        </div>

        <div style="display:flex;gap:8px;padding-top:4px;border-top:1px solid var(--card-border);margin-top:4px">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Menu' : 'Save Menu' }}
          </button>
          <a href="{{ route('menus.index') }}" class="btn btn-outline">
            <i class="bi bi-x-lg"></i> Cancel
          </a>
          @if($isEdit)
            <form method="POST" action="{{ route('menus.destroy', $editMenu->id) }}"
                  style="display:inline;margin-left:auto"
                  onsubmit="return confirm('Delete this menu item?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete Menu
              </button>
            </form>
          @endif
        </div>
      </form>
    </div>
  </div>

  {{-- ═══ SIDEBAR HELP ═══ --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Icon Preview --}}
    <div class="card">
      <div class="card-hd">
        <span class="card-title"><i class="bi bi-eye" style="color:var(--c-purple)"></i>Icon Preview</span>
      </div>
      <div class="card-body" style="text-align:center;padding:24px">
        <div id="iconDemo"
             style="width:52px;height:52px;border-radius:13px;background:var(--c-blue-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
          <i class="bi {{ old('icon', $editMenu->icon ?? 'bi-grid') }}" id="iconBig" style="font-size:24px;color:var(--c-blue)"></i>
        </div>
        <div style="font-size:13px;font-weight:500;color:var(--t1)" id="iconName">
          {{ old('icon', $editMenu->icon ?? 'bi-grid') }}
        </div>
      </div>
    </div>

    {{-- Common icons --}}
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-stars" style="color:var(--c-amber)"></i>Common Icons</span></div>
      <div class="card-body" style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px">
        @foreach([
          'bi-speedometer2','bi-clipboard2-check','bi-people','bi-shield-lock',
          'bi-bar-chart-line','bi-gear','bi-folder2','bi-file-earmark-text',
          'bi-person','bi-calendar3','bi-bell','bi-house',
          'bi-diagram-3','bi-key','bi-map','bi-graph-up',
        ] as $ico)
        <button type="button"
                onclick="document.getElementById('iconInput').value='{{ $ico }}'; updateIcon('{{ $ico }}')"
                style="display:flex;flex-direction:column;align-items:center;gap:4px;padding:8px 4px;border:1.5px solid var(--card-border);border-radius:8px;background:#fff;cursor:pointer;transition:all .12s"
                title="{{ $ico }}"
                onmouseover="this.style.borderColor='#0d2137';this.style.background='#f8fafc'"
                onmouseout="this.style.borderColor='var(--card-border)';this.style.background='#fff'">
          <i class="bi {{ $ico }}" style="font-size:17px;color:var(--t2)"></i>
          <span style="font-size:9px;color:var(--t3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100%;text-align:center">{{ str_replace('bi-','',$ico) }}</span>
        </button>
        @endforeach
      </div>
    </div>

    {{-- Guide --}}
    <div class="card">
      <div class="card-hd"><span class="card-title"><i class="bi bi-info-circle" style="color:var(--c-blue)"></i>Field Guide</span></div>
      <div class="card-body" style="font-size:12.5px;color:var(--t2);line-height:1.75;display:flex;flex-direction:column;gap:8px">
        <div><strong style="color:var(--t1)">Module</strong> — maps to your controller folder, e.g. <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">audits</code></div>
        <div><strong style="color:var(--t1)">Page</strong> — the method/view, e.g. <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">index</code></div>
        <div><strong style="color:var(--t1)">Parent</strong> — set a parent to nest the menu up to 5 levels deep (L1 → L2 → L3 → L4 → L5)</div>
        <div><strong style="color:var(--t1)">Order</strong> — lower number displays first in the sidebar</div>
      </div>
    </div>

  </div>
</div>

<script>
function updateIcon(val) {
  var clean = val.trim().replace(/^bi\s+/,'');
  if (!clean.startsWith('bi-')) clean = 'bi-' + clean;
  document.getElementById('iconBig').className = 'bi ' + clean;
  document.getElementById('iconName').textContent = clean;
  document.getElementById('iconPreview').className = 'bi ' + clean + ' il';
}
document.getElementById('iconInput').addEventListener('input', function() {
  updateIcon(this.value);
});
</script>

@endsection
