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
    <h4 class="mb-4">Available Courses</h4>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isEnrolled = $course->students->isNotEmpty();
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow border-0 h-100 p-0 m-0">
                    <img src="<?php echo e($course->cover_image ? asset('storage/' . $course->cover_image) : asset('assets/publications.jpg')); ?>" class="card-img-top" alt="<?php echo e($course->title); ?>" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-primary"><?php echo e($course->title); ?></h5>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-bar-chart me-1"></i> <?php echo e($course->level ?? 'All Levels'); ?> | 
                            <i class="bi bi-clock me-1"></i> <?php echo e($course->duration ?? 'Self-paced'); ?>

                        </div>
                        <p class="card-text text-muted small mb-3 flex-grow-1"><?php echo e(Str::limit($course->description, 120)); ?></p>

                        <div class="mt-auto">
                            <?php if($isEnrolled): ?>
                                <button class="btn btn-outline-success w-100" disabled>
                                    <i class="bi bi-check-circle me-1"></i> Enrolled
                                </button>
                            <?php else: ?>
                                <form action="<?php echo e(route('tenant.student.courses.enroll', ['tenant' => $tenant->subdomain, 'course' => $course->id])); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Enroll Now
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm text-center py-5">
                    <div class="fs-1 text-muted mb-3"><i class="bi bi-book"></i></div>
                    <h5 class="text-muted">No courses available at the moment.</h5>
                    <p class="mb-0 text-muted small">Check back later for new academic offerings.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <?php echo e($courses->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/student/courses.blade.php ENDPATH**/ ?>