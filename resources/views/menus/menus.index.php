@extends('layouts.app')
@section('page-title','Menu Management')
@section('content')

@php $isEdit = isset($editMenu) && $editMenu; @endphp

<div class="ph">
  <div class="ph-info">
    <h1>Menu Management</h1>
    <p>Build and organise the sidebar navigation structure</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-ok"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="alert alert-err"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
@endif

<div style="display:grid;grid-template-columns:320px 1fr;gap:16px;align-items:start">

  {{-- ═══ FORM PANEL ═══ --}}
  <div class="card" style="position:sticky;top:78px">
    <div class="card-hd">
      <span class="card-title">
        <i class="bi bi-{{ $isEdit ? 'pencil' : 'plus-circle' }}" style="color:var(--c-blue)"></i>
        {{ $isEdit ? 'Edit Menu' : 'Create Menu' }}
      </span>
      @if($isEdit)
        <a href="{{ route('menus.index') }}" class="btn btn-outline btn-sm">
          <i class="bi bi-x-lg"></i> Cancel
        </a>
      @endif
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ $isEdit ? route('menus.update', $editMenu->id) : route('menus.store') }}">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="form-group">
          <label class="form-label">Menu Name <span class="req">*</span></label>
          <input name="name" class="form-control"
                 value="{{ old('name', $editMenu->name ?? '') }}"
                 placeholder="e.g. Audit Management" required>
        </div>

        <div class="form-group">
          <label class="form-label">Module <span class="req">*</span></label>
          <div class="iw">
            <i class="bi bi-folder2 il"></i>
            <input name="module" class="form-control"
                   value="{{ old('module', $editMenu->module ?? '') }}"
                   placeholder="e.g. audits" required>
          </div>
          <div class="form-hint">Controller/folder name (lowercase)</div>
        </div>

        <div class="form-group">
          <label class="form-label">Page <span class="req">*</span></label>
          <div class="iw">
            <i class="bi bi-file-earmark il"></i>
            <input name="page" class="form-control"
                   value="{{ old('page', $editMenu->page ?? '') }}"
                   placeholder="e.g. index" required>
          </div>
          <div class="form-hint">Blade view method (index, create, show…)</div>
        </div>

        <div class="form-group">
          <label class="form-label">Icon</label>
          <div class="iw">
            <i class="bi bi-grid il"></i>
            <input name="icon" class="form-control"
                   value="{{ old('icon', $editMenu->icon ?? '') }}"
                   placeholder="bi-house">
          </div>
          <div class="form-hint">Bootstrap Icons name — e.g. <code style="font-size:11px;background:#f1f5f9;padding:1px 5px;border-radius:4px">bi-house</code></div>
        </div>

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
                  @foreach($m->children as $c)
                    @if(!$isEdit || $c->id !== $editMenu->id)
                      <option value="{{ $c->id }}"
                        {{ old('parent_id', $editMenu->parent_id ?? '') == $c->id ? 'selected' : '' }}>
                        &nbsp;&nbsp;↳ {{ $c->name }}
                      </option>
                    @endif
                  @endforeach
                @endif
              @endif
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input name="order_no" type="number" min="0" class="form-control"
                 value="{{ old('order_no', $editMenu->order_no ?? 0) }}"
                 placeholder="0">
          <div class="form-hint">Lower number appears first</div>
        </div>

        <div style="display:flex;gap:8px">
          <button class="btn btn-primary" style="flex:1">
            <i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Menu' : 'Save Menu' }}
          </button>
          @if($isEdit)
            <a href="{{ route('menus.index') }}" class="btn btn-outline">Cancel</a>
          @endif
        </div>
      </form>
    </div>
  </div>

  {{-- ═══ TREE TABLE ═══ --}}
  <div class="card">
    <div class="card-hd">
      <span class="card-title">
        <i class="bi bi-diagram-3" style="color:var(--c-green)"></i>Menu Structure
      </span>
      <span class="badge b-slate">{{ $menus->count() }} top-level menus</span>
    </div>
    <div style="overflow-x:auto">
      <table class="tbl">
        <thead>
          <tr>
            <th style="min-width:180px">Menu Name</th>
            <th>Module / Page</th>
            <th>Icon</th>
            <th>Order</th>
            <th style="text-align:right;min-width:120px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($menus as $m)
            {{-- L1 --}}
            <tr style="background:#f8fafc">
              <td>
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:26px;height:26px;border-radius:7px;background:var(--c-blue-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi {{ $m->icon ?? 'bi-grid' }}" style="font-size:13px;color:var(--c-blue)"></i>
                  </div>
                  <strong style="font-size:13px">{{ $m->name }}</strong>
                </div>
              </td>
              <td>
                <code style="font-size:11.5px;background:#f1f5f9;padding:2px 6px;border-radius:5px;color:var(--c-purple)">
                  {{ $m->module ?? '-' }}/{{ $m->page ?? '-' }}
                </code>
              </td>
              <td style="font-size:12px;color:var(--t3)">{{ $m->icon ?? '-' }}</td>
              <td style="font-size:12px;color:var(--t2)">{{ $m->order_no ?? 0 }}</td>
              <td style="text-align:right">
                <div style="display:flex;gap:5px;justify-content:flex-end">
                  <a href="{{ route('menus.create', ['edit_id' => $m->id]) }}"
                     class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
                  <form method="POST" action="{{ route('menus.destroy', $m->id) }}"
                        style="display:inline" onsubmit="return confirm('Delete {{ $m->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>

            {{-- L2 children --}}
            @if($m->children && $m->children->count())
              @foreach($m->children as $c)
                <tr>
                  <td>
                    <div style="display:flex;align-items:center;gap:6px;padding-left:20px">
                      <i class="bi bi-arrow-return-right" style="font-size:11px;color:var(--t3)"></i>
                      <span style="font-size:13px">{{ $c->name }}</span>
                    </div>
                  </td>
                  <td>
                    <code style="font-size:11.5px;background:#f1f5f9;padding:2px 6px;border-radius:5px;color:var(--t2)">
                      {{ $c->module ?? '-' }}/{{ $c->page ?? '-' }}
                    </code>
                  </td>
                  <td style="font-size:12px;color:var(--t3)">{{ $c->icon ?? '-' }}</td>
                  <td style="font-size:12px;color:var(--t2)">{{ $c->order_no ?? 0 }}</td>
                  <td style="text-align:right">
                    <div style="display:flex;gap:5px;justify-content:flex-end">
                      <a href="{{ route('menus.create', ['edit_id' => $c->id]) }}"
                         class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
                      <form method="POST" action="{{ route('menus.destroy', $c->id) }}"
                            style="display:inline" onsubmit="return confirm('Delete {{ $c->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>

                {{-- L3 grandchildren --}}
                @if($c->children && $c->children->count())
                  @foreach($c->children as $s)
                    <tr>
                      <td>
                        <div style="display:flex;align-items:center;gap:6px;padding-left:40px">
                          <i class="bi bi-arrow-return-right" style="font-size:10px;color:var(--t3)"></i>
                          <span style="font-size:12.5px;color:var(--t2)">{{ $s->name }}</span>
                        </div>
                      </td>
                      <td>
                        <code style="font-size:11px;background:#f1f5f9;padding:2px 6px;border-radius:5px;color:var(--t3)">
                          {{ $s->module ?? '-' }}/{{ $s->page ?? '-' }}
                        </code>
                      </td>
                      <td style="font-size:12px;color:var(--t3)">{{ $s->icon ?? '-' }}</td>
                      <td style="font-size:12px;color:var(--t2)">{{ $s->order_no ?? 0 }}</td>
                      <td style="text-align:right">
                        <div style="display:flex;gap:5px;justify-content:flex-end">
                          <a href="{{ route('menus.create', ['edit_id' => $s->id]) }}"
                             class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
                          <form method="POST" action="{{ route('menus.destroy', $s->id) }}"
                                style="display:inline" onsubmit="return confirm('Delete {{ $s->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @endif

              @endforeach
            @endif

          @empty
            <tr>
              <td colspan="5" style="text-align:center;color:var(--t3);padding:48px 14px">
                <i class="bi bi-diagram-3" style="font-size:30px;display:block;margin-bottom:10px;opacity:.3"></i>
                No menus yet. Use the form to create your first menu item.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
