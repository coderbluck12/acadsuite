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
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">My Notifications</h3>
            <?php if($notifications->count() > 0): ?>
                <span class="badge bg-primary rounded-pill"><?php echo e($notifications->count()); ?> Total</span>
            <?php endif; ?>
        </div>

        <ul class="list-group list-group-flush">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="list-group-item d-flex justify-content-between align-items-start <?php echo e($notification->read_at ? 'bg-light text-muted' : ''); ?>">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><?php echo e($notification->data['title'] ?? 'Notification'); ?></div>
                        <?php echo e($notification->data['message'] ?? 'You have a new notification.'); ?>

                        <div class="mt-1 small text-muted">
                            <i class="bi bi-clock me-1"></i> <?php echo e($notification->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                    <?php if(empty($notification->read_at)): ?>
                        <div class="d-flex flex-column align-items-end gap-2">
                            <span class="badge bg-danger rounded-pill">New</span>
                            <form action="<?php echo e(route('tenant.student.notifications.read', ['tenant' => $tenant->subdomain, 'notification' => $notification->id])); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem;"><i class="bi bi-check2-all"></i> Mark Read</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <span class="badge bg-secondary text-white rounded-pill">Read</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="list-group-item text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                    No notifications available.
                </li>
            <?php endif; ?>
        </ul>

        <?php if($notifications->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/student/notifications.blade.php ENDPATH**/ ?>