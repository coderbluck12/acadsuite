<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('tenant.admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-2">
    <?php
        $isLimitReached = ($tenant->plan === 'free' || !$tenant->plan) && $tenant->publications()->count() >= 5;
    ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Publications
            <?php if($isLimitReached): ?>
                <span class="badge bg-warning text-dark fs-6 ms-2">Limit Reached (Free)</span>
            <?php endif; ?>
        </h4>
        <button class="btn btn-primary rounded-pill px-4" <?php echo e($isLimitReached ? 'disabled' : ''); ?> data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-plus-circle me-1"></i> Add Publication
        </button>
    </div>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="pubTable" class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Authors</th><th>Journal</th><th>Year</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($publications->firstItem() + $i); ?></td>
                        <td><?php echo e(Str::limit($pub->title, 60)); ?></td>
                        <td class="small"><?php echo e(Str::limit($pub->authors, 40)); ?></td>
                        <td class="small"><?php echo e($pub->journal ?: '—'); ?></td>
                        <td><?php echo e($pub->year ?: '—'); ?></td>
                        <td><span class="badge <?php echo e($pub->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($pub->is_published ? 'Published' : 'Draft'); ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editPubModal<?php echo e($pub->id); ?>"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="<?php echo e(route('tenant.admin.publications.destroy', ['tenant' => $tenant->subdomain, 'publication' => $pub])); ?>" class="d-inline" onsubmit="return confirm('Delete this publication?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($publications->links()); ?>

        </div>
    </div>

    <?php $__currentLoopData = $publications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Edit Publication Modal -->
    <div class="modal fade" id="editPubModal<?php echo e($pub->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Publication</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="<?php echo e(route('tenant.admin.publications.update', ['tenant' => $tenant->subdomain, 'publication' => $pub])); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Publication Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($pub->title); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Author(s)</label>
                            <input type="text" name="authors" class="form-control" value="<?php echo e($pub->authors); ?>" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-8">
                                <label class="form-label fw-semibold">Journal/Conference</label>
                                <input type="text" name="journal" class="form-control" value="<?php echo e($pub->journal); ?>">
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-semibold">Year</label>
                                <input type="number" name="year" class="form-control" value="<?php echo e($pub->year); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link (URL)</label>
                            <input type="url" name="link" class="form-control" value="<?php echo e($pub->url); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Visibility</label>
                            <select name="is_published" class="form-select w-100" required>
                                <option value="1" <?php echo e($pub->is_published ? 'selected' : ''); ?>>Published</option>
                                <option value="0" <?php echo e(!$pub->is_published ? 'selected' : ''); ?>>Draft</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Upload Publication Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel"><i class="bi bi-journal-plus me-2"></i> Upload New Publication</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('tenant.admin.publications.store', ['tenant' => $tenant->subdomain])); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="pubTitle" class="form-label fw-semibold">Publication Title</label>
                        <input type="text" name="title" class="form-control" id="pubTitle" placeholder="Enter title" required>
                    </div>
                    <div class="mb-3">
                        <label for="pubAuthor" class="form-label fw-semibold">Author(s)</label>
                        <input type="text" name="authors" class="form-control" id="pubAuthor" placeholder="Enter author name" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label fw-semibold">Journal/Conference</label>
                            <input type="text" name="journal" class="form-control" placeholder="Optional">
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="YYYY">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link (URL)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label for="pubFile" class="form-label fw-semibold">Upload File</label>
                        <input type="file" name="file" class="form-control" id="pubFile">
                    </div>
                    <div class="mb-3">
                        <label for="visibility" class="form-label fw-semibold">Visibility</label>
                        <select name="is_published" id="visibility" class="form-select w-100" required>
                            <option value="1">Published</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="publicationBtn"><i class="bi bi-upload"></i> Save Publication</button>
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
<script>$(document).ready(function(){ $('#pubTable').DataTable({ destroy: true, ordering: true }); });</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tertgxyp/public_html/acadsuite/resources/views/tenant/admin/publications.blade.php ENDPATH**/ ?>