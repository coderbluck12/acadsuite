

<?php $__env->startPush('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; font-family: 'Outfit', sans-serif; }
        .image-section {
            background: url('<?php echo e(asset('assets/studious_gril.jpg')); ?>') no-repeat center center/cover;
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
            position: relative; overflow: hidden;
        }
        .image-section::before {
            content: ''; position: absolute; inset: 0;
            background-color: rgba(7, 42, 202, 0.4);
        }
        .intro-text { position: relative; z-index: 1; color: white; padding: 3rem; max-width: 600px; }
        .intro-text h1 { font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; }
        .intro-text p { font-size: 1.1rem; opacity: 0.9; line-height: 1.7; }
        .form-section { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #fff; padding: 2rem; }
        .login-container { width: 100%; max-width: 380px; }
        .btn-custom { border-radius: 8px; padding: 0.65rem 1rem; font-weight: 600; font-size: 0.95rem; }
        .form-control { border-radius: 8px; padding: 0.65rem 1rem; border: 1.5px solid #dee2e6; transition: border-color 0.2s; }
        .form-control:focus { border-color: #3F51B5; box-shadow: 0 0 0 3px rgba(63,81,181,0.15); }
        @media (max-width: 768px) { 
            .image-section { display: none; } 
            .form-section { 
                min-height: 100vh;
                background: url('<?php echo e(asset('assets/studious_gril.jpg')); ?>') no-repeat center center/cover;
                position: relative;
                z-index: 1;
            }
            .form-section::before {
                content: "";
                position: absolute;
                inset: 0;
                background-color: rgba(7, 42, 202, 0.4);
                z-index: -1;
            }
            .login-container {
                background: rgba(255, 255, 255, 0.9);
                padding: 2rem;
                border-radius: 8px;
                margin: auto;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>
<div class="container-fluid h-100">
    <div class="row h-100">
        
        <div class="col-md-8 image-section">
            <div class="intro-text">
                <h1>Get Access to Educational Materials</h1>
                <p>Join <strong><?php echo e($tenant->name); ?></strong> portal to explore study guides, past questions, and learning resources designed to help you excel in your academic journey.</p>
            </div>
        </div>
        
        <div class="col-md-4 form-section">
            <div class="login-container">
                <div class="mb-4 text-start">
                    <?php if($tenant->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $tenant->avatar)); ?>" alt="Logo" width="100">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="Logo" width="100">
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold mb-1">Sign In</h4>
                <p class="text-muted">Welcome to <?php echo e($tenant->name); ?></p>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger py-2"><?php echo e($errors->first()); ?></div>
                <?php endif; ?>
                <?php if(session('success')): ?>
                    <div class="alert alert-success py-2"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                
                <form method="POST" action="/login">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom w-100">Sign in</button>
                    <div class="text-center mt-3">
                        <p class="mb-0">Don't have an account yet?
                            
                            <a href="/register" class="fw-semibold text-decoration-none">Sign up</a>
                        </p>
                    </div>
                </form>
                <div class="mt-5 small text-muted">&copy; <?php echo e(date('Y')); ?>. ALL RIGHTS RESERVED.</div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/auth/login.blade.php ENDPATH**/ ?>