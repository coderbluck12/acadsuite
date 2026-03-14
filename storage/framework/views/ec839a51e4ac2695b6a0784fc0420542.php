

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Review Submissions: <?php echo e($assignment->title); ?></h4>
        <a href="<?php echo e(route('tenant.admin.assignments.index', ['tenant' => $tenant->subdomain])); ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="submissionTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Submission File</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Grading Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignment->submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($submission->student->name); ?></td>
                        <td>
                            <?php if($submission->file_path): ?>
                                <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>" class="btn btn-sm btn-outline-primary" download target="_blank">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No file</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($submission->status === 'graded'): ?>
                                <span class="badge bg-success">Graded</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Submitted</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($submission->created_at->format('d M Y, h:i A')); ?></td>
                        <td>
                            <?php if($submission->status === 'graded'): ?>
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-check-circle"></i> Graded
                                </button>
                            <?php else: ?>
                                <form action="<?php echo e(route('tenant.admin.assignments.grade', ['tenant' => $tenant->subdomain, 'assignment' => $assignment->id, 'submission' => $submission->id])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Mark this submission as graded?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Mark as Graded
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#submissionTable').DataTable({ destroy: true, ordering: true });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/admin/assignments-show.blade.php ENDPATH**/ ?>