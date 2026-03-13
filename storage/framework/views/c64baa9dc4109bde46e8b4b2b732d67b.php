<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4>Login</h4>
<form method="POST" action="<?php echo e(route('login')); ?>"><?php echo csrf_field(); ?>
<div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required></div>
<div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
<div class="mb-3 form-check"><input type="checkbox" class="form-check-input" name="remember" value="1"><label class="form-check-label">Remember me</label></div>
<div class="d-flex justify-content-between">
<button class="btn btn-primary">Login</button>
<a href="<?php echo e(route('password.request')); ?>">Forgot password?</a>
</div>
</form>
</div></div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /workspaces/Audit-Management-System/resources/views/auth/login.blade.php ENDPATH**/ ?>