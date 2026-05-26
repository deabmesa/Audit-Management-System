@extends('layouts.app')
@section('page-title','Role Management')
@section('content')
<div class="ph">
  <div class="ph-info"><h1>Role Management</h1><p>Define roles and configure access permissions</p></div>
  <div class="ph-acts"><a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Role</a></div>
</div>
<div class="card">
  <div class="card-hd"><span class="card-title"><i class="bi bi-shield-lock" style="color:var(--c-purple)"></i>All Roles</span></div>
  <div style="overflow-x:auto">
    <table class="tbl">
      <thead><tr><th>#</th><th>Role Name</th><th>Permissions</th><th style="text-align:right">Actions</th></tr></thead>
      <tbody>
        @forelse($roles as $index => $role)
        <tr>
          <td style="color:var(--t3);font-size:12px">{{ $roles->firstItem()+$index }}</td>
          <td style="font-weight:600">{{ $role->name }}</td>
          <td>
            <div style="display:flex;flex-wrap:wrap;gap:4px">
              @foreach($role->permissions as $p)
              <span class="badge b-slate">{{ $p->name }}</span>
              @endforeach
            </div>
          </td>
          <td style="text-align:right">
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="{{ route('roles.edit',$role) }}" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form method="POST" action="{{ route('roles.destroy',$role) }}" style="display:inline" onsubmit="return confirm('Delete this role?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:var(--t3);padding:40px">No roles found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 18px;border-top:1px solid var(--card-border)">{{ $roles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
