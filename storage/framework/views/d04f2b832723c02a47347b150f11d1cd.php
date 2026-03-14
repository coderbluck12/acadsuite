

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <h4 class="mb-4">Student Management</h4>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="studentTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($students->firstItem() + $i); ?></td>
                        <td><?php echo e($student->name); ?></td>
                        <td><?php echo e($student->email); ?></td>
                        <td>
                            <?php if($student->status === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif($student->status === 'rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($student->created_at->format('d M Y')); ?></td>
                        <td>
                            <?php if($student->status !== 'approved'): ?>
                            <form method="POST" action="<?php echo e(route('tenant.admin.students.approve', ['tenant' => $tenant->subdomain, 'user' => $student])); ?>" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-success btn-sm me-1"><i class="bi bi-check-circle"></i> Approve</button>
                            </form>
                            <?php endif; ?>
                            <?php if($student->status !== 'rejected'): ?>
                            <form method="POST" action="<?php echo e(route('tenant.admin.students.reject', ['tenant' => $tenant->subdomain, 'user' => $student])); ?>" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Reject</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No students registered yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php echo e($students->links()); ?>

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
    $(document).ready(function() {
        $('#studentTable').DataTable({ destroy: true, pageLength: 10, lengthMenu: [10, 25, 50], ordering: true });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/admin/students.blade.php ENDPATH**/ ?>