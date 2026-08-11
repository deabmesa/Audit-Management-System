<div class="mb-3"><label>Name</label><input class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}"></div>
<div class="mb-3"><label>Email</label><input class="form-control" name="email" value="{{ old('email', $user->email ?? '') }}"></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password"></div>
<div class="mb-3"><label>Role</label>
<select class="form-select" name="role">
@foreach(['Admin','Auditor','Reviewer'] as $role)
<option value="{{ $role }}" @selected(old('role', $user->role ?? '') === $role)>{{ $role }}</option>
@endforeach
</select></div>
