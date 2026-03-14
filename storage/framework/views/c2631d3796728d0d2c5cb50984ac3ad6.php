

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <?php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->assignments()->count() >= 5;
    ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Assignments
            <?php if($isLimitReached): ?>
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            <?php endif; ?>
        </h4>
        <button class="btn btn-primary rounded-pill px-4" <?php echo e($isLimitReached ? 'disabled' : ''); ?> data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
            <i class="bi bi-plus-circle me-1"></i> Create Assignment
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="assignmentTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Course</th><th>Due Date</th><th>Submissions</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($assignments->firstItem() + $i); ?></td>
                        <td><?php echo e($assignment->title); ?></td>
                        <td><?php echo e($assignment->course?->title ?? '—'); ?></td>
                        <td><?php echo e($assignment->due_date?->format('d M Y') ?? '—'); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo e($assignment->submissions->count()); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('tenant.admin.assignments.show', ['tenant' => $tenant->subdomain, 'assignment' => $assignment])); ?>" class="btn btn-sm btn-outline-success me-1" title="View Submissions"><i class="bi bi-eye"></i></a>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editAssignmentModal<?php echo e($assignment->id); ?>"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="<?php echo e(route('tenant.admin.assignments.destroy', ['tenant' => $tenant->subdomain, 'assignment' => $assignment])); ?>" class="d-inline" onsubmit="return confirm('Delete?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($assignments->links()); ?>

        </div>
    </div>

    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Edit Assignment Modal -->
    <div class="modal fade" id="editAssignmentModal<?php echo e($assignment->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Assignment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="<?php echo e(route('tenant.admin.assignments.update', ['tenant' => $tenant->subdomain, 'assignment' => $assignment])); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assignment Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($assignment->title); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description / Instructions</label>
                            <textarea class="form-control" name="description" rows="3"><?php echo e($assignment->description); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Related Course</label>
                            <select name="course_id" class="form-select">
                                <option value="">-- No Course --</option>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>" <?php echo e($assignment->course_id == $course->id ? 'selected' : ''); ?>><?php echo e($course->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="<?php echo e($assignment->due_date?->format('Y-m-d')); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Attach File (Optional)</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Create Assignment Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createAssignmentModalLabel"><i class="bi bi-file-earmark-text me-2"></i> Create Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('tenant.admin.assignments.store', ['tenant' => $tenant->subdomain])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Assignment Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Instructions</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Provide instructions..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Related Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- No Course --</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"><?php echo e($course->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Attach File (Optional)</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload"></i> Create Assignment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function(){ $('#assignmentTable').DataTable({ destroy: true, ordering: true }); });</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/admin/assignments.blade.php ENDPATH**/ ?>