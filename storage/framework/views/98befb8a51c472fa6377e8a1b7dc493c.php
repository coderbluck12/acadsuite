

<?php $__env->startSection('sidebar-nav'); ?>
<nav>
    <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="<?php echo e(request()->routeIs('superadmin.dashboard') ? 'active' : ''); ?> m-2">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="<?php echo e(route('superadmin.academics.index')); ?>" class="<?php echo e(request()->routeIs('superadmin.academics*') ? 'active' : ''); ?> m-2">
        <i class="bi bi-people me-2"></i> All Academics
    </a>
    <hr class="border-white opacity-25 mx-3">
    <form method="POST" action="<?php echo e(route('superadmin.logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" style="background:none;border:none;color:white;padding:11px 20px;width:100%;text-align:left;border-radius:5px;font-size:0.9rem;">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <h4 class="mb-4">Pentagonware — HQ Dashboard</h4>

    
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="bi bi-people-fill fs-4 text-primary"></i></div>
                    <div><div class="fs-2 fw-bold text-primary"><?php echo e($stats['total']); ?></div><div class="text-muted small">Total Academics</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="bi bi-check-circle-fill fs-4 text-success"></i></div>
                    <div><div class="fs-2 fw-bold text-success"><?php echo e($stats['active']); ?></div><div class="text-muted small">Active Portals</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="bi bi-x-circle-fill fs-4 text-danger"></i></div>
                    <div><div class="fs-2 fw-bold text-danger"><?php echo e($stats['inactive']); ?></div><div class="text-muted small">Inactive Portals</div></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3"><i class="bi bi-calendar-plus-fill fs-4 text-warning"></i></div>
                    <div><div class="fs-2 fw-bold text-warning"><?php echo e($stats['thisMonth']); ?></div><div class="text-muted small">New This Month</div></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Recent Registrations</h5>
            <a href="<?php echo e(route('superadmin.academics.index')); ?>" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Academic / Owner</th>
                            <th>Subdomain</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentAcademics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $academic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($academic->name); ?></div>
                                <div class="text-muted small"><?php echo e($academic->owner_name); ?></div>
                            </td>
                            <td><code><?php echo e($academic->subdomain); ?>.<?php echo e(config('app.base_domain')); ?></code></td>
                            <td><?php echo e($academic->email); ?></td>
                            <td>
                                <?php if($academic->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($academic->created_at->format('d M Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('superadmin.academics.show', $academic)); ?>" class="btn btn-sm btn-outline-primary me-1">View</a>
                                <form method="POST" action="<?php echo e(route('superadmin.academics.toggle', $academic)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm <?php echo e($academic->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>">
                                        <?php echo e($academic->is_active ? 'Deactivate' : 'Activate'); ?>

                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No academics registered yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/superadmin/dashboard.blade.php ENDPATH**/ ?>