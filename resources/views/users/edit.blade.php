@extends('layouts.app')
@section('page-title','Edit User')
@section('content')
<div class="ph">
  <div class="ph-info"><h1>Edit User</h1><p>Update user information and role</p></div>
  <div class="ph-acts"><a href="{{ route('users.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Back</a></div>
</div>
<div style="max-width:580px">
  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-person-gear" style="color:var(--c-amber)"></i>User Details</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('users.update',$user) }}">@csrf @method('PUT')
        @include('users.form')
        <div style="display:flex;gap:8px">
          <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Update User</button>
          <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
