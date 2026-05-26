@extends('layouts.app')
@section('page-title','User Management')
@section('content')
<div class="ph">
  <div class="ph-info"><h1>User Management</h1><p>Manage system users and their roles</p></div>
  <div class="ph-acts">
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add User</a>
  </div>
</div>
<div class="card">
  <div class="card-hd">
    <span class="card-title"><i class="bi bi-people" style="color:var(--c-blue)"></i>All Users</span>
    <div class="iw" style="width:220px">
      <i class="bi bi-search il"></i>
      <input class="form-control" placeholder="Search users…" style="height:34px">
    </div>
  </div>
  <div style="overflow-x:auto">
    <table class="tbl">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
      <tbody>
        @forelse($users as $i => $user)
        <tr>
          <td style="color:var(--t3);font-size:12px">{{ $i+1 }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0d2137,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:11px;flex-shrink:0">
                {{ strtoupper(substr($user->name,0,1)) }}
              </div>
              <span style="font-weight:500">{{ $user->name }}</span>
            </div>
          </td>
          <td style="color:var(--t2)">{{ $user->email }}</td>
          <td><span class="badge b-purple">{{ $user->roles->first()->name ?? '—' }}</span></td>
          <td>
            <form method="POST" action="{{ route('users.toggle-status', $user) }}" style="display:inline">
              @csrf @method('PATCH')
              <button type="submit"
                style="display:inline-flex;align-items:center;gap:6px;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all .15s;
                       {{ $user->is_active ? 'background:#dcfce7;color:#16a34a' : 'background:#fee2e2;color:#dc2626' }}"
                title="Click to {{ $user->is_active ? 'deactivate' : 'activate' }}">
                <span style="width:7px;height:7px;border-radius:50%;background:currentColor;display:inline-block"></span>
                {{ $user->is_active ? 'Active' : 'Inactive' }}
              </button>
            </form>
          </td>
          <td style="text-align:right">
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="{{ route('users.edit',$user) }}" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form method="POST" action="{{ route('users.destroy',$user) }}" style="display:inline" onsubmit="return confirm('Delete this user?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:var(--t3);padding:40px">
          <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:8px;opacity:.35"></i>No users found.
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
