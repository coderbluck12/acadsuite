

<?php $__env->startPush('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #1a237e, #3F51B5); min-height: 100vh; display: flex; align-items: center; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="text-center text-white px-4" style="max-width:600px;">
        <div class="mb-4" style="font-size:5rem;">🎉</div>
        <h1 class="fw-bold display-5 mb-3">Your Suite is Live!</h1>
        <?php if(session('subdomain')): ?>
        <p class="lead opacity-90 mb-4">Your academic portal is ready at:</p>
        <a href="<?php echo e(session('portal_url')); ?>" target="_blank"
           class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold shadow mb-4">
            🌐 <?php echo e(session('subdomain')); ?>.<?php echo e(config('app.base_domain')); ?> →
        </a>
        <?php endif; ?>
        <p class="opacity-75 mb-4">Login with the email and password you registered with to access your admin panel and start adding publications, courses, and resources.</p>
        <a href="<?php echo e(route('landing.home')); ?>" class="btn btn-outline-light rounded-pill px-4">← Back to AcadSuite</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/landing/success.blade.php ENDPATH**/ ?>