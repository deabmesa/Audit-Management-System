<div class="form-group">
  <label class="form-label">Full Name <span class="req">*</span></label>
  <input class="form-control" name="name" value="{{ old('name',$user->name??'') }}" placeholder="Full name" required>
</div>
<div class="form-group">
  <label class="form-label">Email Address <span class="req">*</span></label>
  <input type="email" class="form-control" name="email" value="{{ old('email',$user->email??'') }}" placeholder="Email address" required>
</div>
<div class="form-group">
  <label class="form-label">Password {{ isset($user)?'(leave blank to keep current)':'' }}</label>
  <input type="password" class="form-control" name="password" placeholder="Password" {{ isset($user)?'':'required' }}>
</div>
<div class="form-group">
  <label class="form-label">Role <span class="req">*</span></label>
  <select class="form-select" name="role" required>
    <option value="">Select role</option>
    @foreach(['Admin','Auditor','Reviewer'] as $r)
    <option value="{{ $r }}" @selected(old('role',$user->role??'')===$r)>{{ $r }}</option>
    @endforeach
  </select>
</div>
