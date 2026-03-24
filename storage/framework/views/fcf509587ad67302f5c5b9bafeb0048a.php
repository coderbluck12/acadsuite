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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All Academics</h4>
        <span class="badge bg-primary rounded-pill fs-6"><?php echo e($academics->total()); ?> Total</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="academicsTable" class="table align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Suite Name / Owner</th>
                            <th>Portal URL</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $academics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $academic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($academics->firstItem() + $i); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($academic->name); ?></div>
                                <div class="text-muted small"><?php echo e($academic->owner_name); ?></div>
                            </td>
                            <td><a href="http://<?php echo e($academic->subdomain); ?>.<?php echo e(config('app.base_domain')); ?>" target="_blank" class="text-decoration-none small"><code><?php echo e($academic->subdomain); ?>.<?php echo e(config('app.base_domain')); ?></code></a></td>
                            <td class="small"><?php echo e($academic->email); ?></td>
                            <td>
                                <?php if($academic->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo e($academic->users()->where('role','student')->count()); ?></td>
                            <td class="small"><?php echo e($academic->created_at->format('d M Y')); ?></td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="<?php echo e(route('superadmin.academics.show', $academic)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    <form method="POST" action="<?php echo e(route('superadmin.academics.toggle', $academic)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button class="btn btn-sm <?php echo e($academic->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>">
                                            <?php echo e($academic->is_active ? 'Deactivate' : 'Activate'); ?>

                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('superadmin.academics.destroy', $academic)); ?>" class="d-inline" onsubmit="return confirm('Delete this portal? All data will be lost.')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-muted py-5">No academics registered yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($academics->hasPages()): ?>
        <div class="card-footer bg-white border-0 py-3">
            <?php echo e($academics->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/superadmin/academics/index.blade.php ENDPATH**/ ?>