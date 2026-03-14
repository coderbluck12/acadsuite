

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
    <h4 class="mb-4">Assignments</h4>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $submission = $assignment->submissions->first();
                $isSubmitted = $submission !== null;
                $isPastDue = $assignment->due_date && $assignment->due_date->isPast();
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow border-0 h-100 p-0 m-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary"><?php echo e($assignment->title); ?></h5>
                        <p class="card-text text-muted small mb-3"><?php echo e(Str::limit($assignment->description, 100)); ?></p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <?php if($assignment->due_date): ?>
                                    <span class="badge <?php echo e($isPastDue && !$isSubmitted ? 'bg-danger' : 'bg-secondary'); ?>">
                                        Due: <?php echo e($assignment->due_date->format('d M Y')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Due Date</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if($isSubmitted): ?>
                                    <?php if($submission->status === 'graded'): ?>
                                        <span class="badge bg-primary"><i class="bi bi-award"></i> Graded</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Submitted</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if($assignment->file_path): ?>
                            <a href="<?php echo e(asset('storage/' . $assignment->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary w-100 mb-3">
                                <i class="bi bi-download"></i> Download Material
                            </a>
                        <?php endif; ?>

                        <?php if(!$isSubmitted): ?>
                            <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#submitModal<?php echo e($assignment->id); ?>">
                                <i class="bi bi-upload"></i> Submit Work
                            </button>
                        <?php elseif($submission->status === 'graded'): ?>
                            <button class="btn btn-primary btn-sm w-100" disabled>
                                <i class="bi bi-award"></i> Graded
                            </button>
                        <?php else: ?>
                            <button class="btn btn-success btn-sm w-100" disabled>
                                <i class="bi bi-check-all"></i> Completed
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Submit Modal -->
            <?php if(!$isSubmitted): ?>
            <div class="modal fade" id="submitModal<?php echo e($assignment->id); ?>" tabindex="-1" aria-labelledby="submitModalLabel<?php echo e($assignment->id); ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="submitModalLabel<?php echo e($assignment->id); ?>"><i class="bi bi-upload me-2"></i> Submit Assignment</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="fw-bold mb-3 text-secondary"><?php echo e($assignment->title); ?></h6>
                            <form action="<?php echo e(route('tenant.student.assignments.submit', ['tenant' => $tenant->subdomain, 'assignment' => $assignment])); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Upload File <span class="text-danger">*</span></label>
                                    <input type="file" name="file" class="form-control" required>
                                    <small class="text-muted">Max file size: 20MB</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Additional Comments</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Add any notes for the instructor..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-semibold">Submit Assignment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm text-center py-5">
                    <div class="fs-1 text-muted mb-3"><i class="bi bi-journal-x"></i></div>
                    <h5 class="text-muted">No assignments available at the moment.</h5>
                    <p class="mb-0 text-muted small">Check back later or contact your instructor.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <?php echo e($assignments->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/student/assignments.blade.php ENDPATH**/ ?>