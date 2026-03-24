<?php $__env->startPush('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f4ff; }
        .register-hero { background: linear-gradient(135deg, #1a237e, #3F51B5); color: white; padding: 3rem 0 2rem; }
        .form-card { border-radius: 1rem; border: none; box-shadow: 0 8px 40px rgba(0,0,0,0.1); }
        .form-control, .form-select { border-radius: 8px; padding: 0.65rem 1rem; border: 1.5px solid #dee2e6; }
        .form-control:focus { border-color: #3F51B5; box-shadow: 0 0 0 3px rgba(63,81,181,0.15); }
        .availability-badge { display: inline-block; font-size: 0.78rem; padding: 0.2rem 0.6rem; border-radius: 50px; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
<div class="register-hero text-center mb-0">
    <h1 class="fw-bold display-6 mb-2">Create Your Academic Suite</h1>
    <p class="opacity-90">Your portal will be live at <code style="background:rgba(255,255,255,0.15);padding:0.2rem 0.6rem;border-radius:5px;">yourname.<?php echo e(config('app.base_domain')); ?></code></p>
</div>

<div class="container py-5" style="max-width:620px;">
    <div class="card form-card p-4 p-md-5">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger"><ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('landing.register.store')); ?>">
            <?php echo csrf_field(); ?>
            <h5 class="fw-bold mb-4">Your Details</h5>
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name *</label>
                <input type="text" name="owner_name" class="form-control" value="<?php echo e(old('owner_name')); ?>" required placeholder="e.g. Dr. Jane Doe">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Suite / Portal Name *</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required placeholder="e.g. Dr. Jane Doe Academic Hub">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address *</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Choose Your Subdomain *
                </label>
                <div class="input-group">
                    <input type="text" name="subdomain" id="subdomainInput" class="form-control" value="<?php echo e(old('subdomain')); ?>" required placeholder="yourname" pattern="[a-z0-9\-]+" style="text-transform:lowercase;">
                    <span class="input-group-text">.<?php echo e(config('app.base_domain')); ?></span>
                </div>
                <div id="subdomainStatus" class="mt-1"></div>
                <div class="form-text">Only lowercase letters, numbers, and hyphens. Min 3 characters.</div>
            </div>
            <hr class="my-4">
            <h5 class="fw-bold mb-4">Set Your Password</h5>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password *</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold py-3 rounded-pill">🚀 Launch My Suite</button>
            <div class="text-center mt-3 small text-muted">Already have a suite? <a href="#">Login here</a></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const input = document.getElementById('subdomainInput');
    const status = document.getElementById('subdomainStatus');
    let timer;

    input.addEventListener('input', function() {
        const val = this.value.toLowerCase().replace(/[^a-z0-9\-]/g, '');
        this.value = val;
        clearTimeout(timer);
        if (val.length < 3) { status.innerHTML = ''; return; }
        status.innerHTML = '<span class="availability-badge bg-light text-muted">Checking...</span>';
        timer = setTimeout(() => {
            fetch(`<?php echo e(route('landing.check-subdomain')); ?>?subdomain=${val}`)
                .then(r => r.json())
                .then(data => {
                    status.innerHTML = `<span class="availability-badge ${data.available ? 'bg-success text-white' : 'bg-danger text-white'}">${data.message}</span>`;
                });
        }, 500);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/landing/register.blade.php ENDPATH**/ ?>