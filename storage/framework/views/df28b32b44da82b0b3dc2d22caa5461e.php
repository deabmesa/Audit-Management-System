<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Management System</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">AuditMS</a>
            <?php if(auth()->guard()->check()): ?>
                <div class="navbar-nav">
                    <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                    <a class="nav-link" href="<?php echo e(route('audits.index')); ?>">Audits</a>
                    <?php if(auth()->user()->hasRole('Admin')): ?>
                        <a class="nav-link" href="<?php echo e(route('users.index')); ?>">Users</a>
                        <a class="nav-link" href="<?php echo e(route('users.logs')); ?>">Activity Logs</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <?php if(auth()->guard()->check()): ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?> <button class="btn btn-sm btn-light">Logout</button></form>
            <?php else: ?>
                <a class="btn btn-sm btn-light" href="<?php echo e(route('login')); ?>">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">
    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
</div>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /workspaces/Audit-Management-System/resources/views/layouts/app.blade.php ENDPATH**/ ?>