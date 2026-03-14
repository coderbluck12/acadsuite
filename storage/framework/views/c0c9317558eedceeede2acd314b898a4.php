

<?php $__env->startSection('sidebar-nav'); ?>
<nav>
    <a href="<?php echo e(route('tenant.student.dashboard', ['tenant' => $tenant->subdomain])); ?>" class="<?php echo e(request()->routeIs('tenant.student.dashboard') ? 'active' : ''); ?> m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="<?php echo e(route('tenant.student.courses', ['tenant' => $tenant->subdomain])); ?>" class="<?php echo e(request()->routeIs('tenant.student.courses') ? 'active' : ''); ?> m-2">
        <i class="bi bi-book me-2"></i> Courses
    </a>
    <a href="<?php echo e(route('tenant.student.assignments', ['tenant' => $tenant->subdomain])); ?>" class="<?php echo e(request()->routeIs('tenant.student.assignments') ? 'active' : ''); ?> m-2">
        <i class="bi bi-file-earmark-text me-2"></i> Assignments
    </a>
    <a href="<?php echo e(route('tenant.student.notifications', ['tenant' => $tenant->subdomain])); ?>" class="<?php echo e(request()->routeIs('tenant.student.notifications') ? 'active' : ''); ?> m-2">
        <i class="bi bi-bell me-2"></i> Notifications
        <?php if($notifications > 0): ?><span class="badge bg-danger ms-1"><?php echo e($notifications); ?></span><?php endif; ?>
    </a>
    <hr class="border-white opacity-25 mx-3">
    <a href="<?php echo e(route('tenant.home', ['tenant' => $tenant->subdomain])); ?>" class="m-2" target="_blank">
        <i class="bi bi-box-arrow-up-right me-2"></i> Portal Home
    </a>
    <form method="POST" action="<?php echo e(route('tenant.logout', ['tenant' => $tenant->subdomain])); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <h4 class="mb-4">Welcome, <?php echo e($user->name); ?> 👋</h4>

    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="bi bi-book-fill fs-4 text-primary"></i></div>
                    <div><div class="fs-2 fw-bold text-primary"><?php echo e($courses->count()); ?></div><div class="text-muted small">My Enrolled Courses</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3"><i class="bi bi-file-earmark-text-fill fs-4 text-warning"></i></div>
                    <div><div class="fs-2 fw-bold text-warning"><?php echo e($assignments->count()); ?></div><div class="text-muted small">Pending Assignments</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="bi bi-bell-fill fs-4 text-danger"></i></div>
                    <div><div class="fs-2 fw-bold text-danger"><?php echo e($notifications); ?></div><div class="text-muted small">Unread Notifications</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                    My Courses
                    <a href="<?php echo e(route('tenant.student.courses', ['tenant' => $tenant->subdomain])); ?>" class="btn btn-sm btn-outline-primary rounded-pill">Enroll More</a>
                </div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item">
                        <div class="fw-semibold"><?php echo e($course->title); ?></div>
                        <small class="text-muted"><?php echo e($course->level); ?> • <?php echo e($course->duration); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item text-muted text-center py-3">You are not enrolled in any courses yet. <br> <a href="<?php echo e(route('tenant.student.courses', ['tenant' => $tenant->subdomain])); ?>" class="btn btn-primary btn-sm mt-2 rounded-pill">Browse Courses</a></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                    Recent Assignments
                    <a href="<?php echo e(route('tenant.student.assignments', ['tenant' => $tenant->subdomain])); ?>" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item">
                        <div class="fw-semibold"><?php echo e($assignment->title); ?></div>
                        <?php if($assignment->due_date): ?>
                        <small class="text-<?php echo e($assignment->due_date->isPast() ? 'danger' : 'muted'); ?>">
                            Due: <?php echo e($assignment->due_date->format('d M Y')); ?>

                        </small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item text-muted text-center py-3">No assignments yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/student/dashboard.blade.php ENDPATH**/ ?>