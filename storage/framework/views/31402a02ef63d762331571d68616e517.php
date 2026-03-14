

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Resources</h4>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-plus-circle me-1"></i> Add Resource
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="resTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($resources->firstItem() + $i); ?></td>
                        <td><?php echo e(Str::limit($res->title, 60)); ?></td>
                        <td><?php echo e(strtoupper($res->file_type)); ?></td>
                        <td><span class="badge <?php echo e($res->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($res->is_published ? 'Published' : 'Draft'); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('tenant.admin.resources.edit', ['tenant' => $tenant->subdomain, 'resource' => $res])); ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('tenant.admin.resources.destroy', ['tenant' => $tenant->subdomain, 'resource' => $res])); ?>" class="d-inline" onsubmit="return confirm('Delete this resource?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($resources->links()); ?>

        </div>
    </div>
</div>

<!-- Upload Resource Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel"><i class="bi bi-folder-plus me-2"></i> Upload New Resource</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('tenant.admin.resources.store', ['tenant' => $tenant->subdomain])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="pubTitle" class="form-label fw-semibold">Resource Title</label>
                        <input type="text" name="title" class="form-control" id="pubTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="pubDesc" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" id="pubDesc" placeholder="Optional description..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pubFile" class="form-label fw-semibold">Upload File</label>
                        <input type="file" name="file" class="form-control" id="pubFile" required>
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload p-1"></i> Upload</button>
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
<script>$(document).ready(function(){ $('#resTable').DataTable({ destroy: true, ordering: true }); });</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\acadsuite\resources\views/tenant/admin/resources.blade.php ENDPATH**/ ?>