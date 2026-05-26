@extends('layouts.app')
@section('page-title', isset($role) ? 'Edit Role' : 'Create Role')
@section('content')
<div class="ph">
  <div class="ph-info">
    <h1>{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h1>
    <p>Define role name and assign menu access permissions</p>
  </div>
  <div class="ph-acts"><a href="{{ route('roles.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Back</a></div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:16px;align-items:start">

  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-shield" style="color:var(--c-purple)"></i>Role Info</span></div>
    <div class="card-body">
      <form method="POST" action="{{ isset($role)?route('roles.update',$role):route('roles.store') }}" id="roleForm">
        @csrf @if(isset($role)) @method('PUT') @endif
        <div class="form-group">
          <label class="form-label">Role Name <span class="req">*</span></label>
          <input type="text" name="name" value="{{ $role->name??'' }}" class="form-control" placeholder="e.g. Senior Auditor" required>
        </div>
        <button class="btn btn-primary" style="width:100%">
          <i class="bi bi-check-lg"></i> {{ isset($role) ? 'Update Role' : 'Save Role' }}
        </button>
      </form>
    </div>
  </div>

    <div class="card">
    <div class="card-hd">
      <span class="card-title"><i class="bi bi-key" style="color:var(--c-amber)"></i>Menu Access Permissions</span>
      <label style="display:flex;align-items:center;gap:7px;font-size:13px;color:var(--t2);cursor:pointer;font-weight:normal">
        <input type="checkbox" id="selectAll" style="accent-color:#0d2137;width:14px;height:14px"> Select All
      </label>
    </div>
    <div class="card-body">
      @if($menus->isEmpty())
        <div style="text-align:center;padding:24px;color:var(--t3);font-size:13px">
          <i class="bi bi-info-circle" style="font-size:22px;margin-bottom:8px;display:block"></i>
          No menus with permissions defined yet.<br>
          <span style="font-size:12px">Set a <strong>Permission</strong> key on your menus first.</span>
        </div>
      @else
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px">
        @foreach($menus as $menu)
          @if(!empty($menu->permission))
          <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid var(--card-border);border-radius:8px;cursor:pointer;font-size:13px;transition:border-color .12s,background .12s"
                 onmouseover="this.style.borderColor='#0d2137';this.style.background='#f8fafc'"
                 onmouseout="this.style.borderColor='var(--card-border)';this.style.background=''">
            <input type="checkbox" name="permissions[]" value="{{ $menu->permission }}" form="roleForm"
                   class="perm-cb" style="accent-color:#0d2137;width:14px;height:14px;flex-shrink:0"
                   {{ isset($role) && $role->permissions->pluck('name')->contains($menu->permission) ? 'checked' : '' }}>
            <span style="flex:1">{{ $menu->name }}</span>
            <code style="font-size:10px;color:var(--t3);background:#f1f5f9;padding:1px 5px;border-radius:4px;white-space:nowrap">{{ $menu->permission }}</code>
          </label>
          @endif
        @endforeach
      </div>
      @endif
    </div>
  </div>

</div>
<script>
document.getElementById('selectAll').addEventListener('change',function(){
  document.querySelectorAll('.perm-cb').forEach(c=>c.checked=this.checked);
});
</script>
@endsection
